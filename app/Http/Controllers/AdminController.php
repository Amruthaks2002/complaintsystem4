<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateReportJob;
use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use App\Models\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComplaintReportMail;
use App\Jobs\GenerateCsvAndSendLink;


class AdminController extends Controller
{
    public function showLogin()
    {
        return view('auth.admin_login');
    }

    public function dashboard(Request $request)
{
    $status = $request->query('status');
    $departmentId = $request->query('department');

    $query = Complaint::with('user', 'department');

    if ($status) {
        $query->where('status', $status);
    }

    if ($departmentId) {
        $query->where('department_id', $departmentId);
    }

    $complaints = $query->orderBy('created_at', 'desc')->paginate(10);

    $departments = \App\Models\Department::all(); 

    return view('admin.dashboard', compact('complaints', 'departments'));
}


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (auth()->user()->role === 'admin') {
                return redirect('/dashboard/admin');
            } else {
                Auth::logout();

                return back()->withErrors(['email' => 'Unauthorized login.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function showComplaint($id)
    {
        $complaint = Complaint::with([
            'user',
            'department',
            'responses',
            'statusHistories.user',
        ])->findOrFail($id);

        return response()->json([
            'id' => $complaint->id,
            'title' => $complaint->title,
            'description' => $complaint->description,
            'student' => $complaint->user->name,
            'department' => $complaint->department->name,
            'file' => $complaint->file_path,
            'responses' => $complaint->responses->map(function ($r) {
                return [
                    'by' => $r->admin_id ? 'Admin' : 'Department',
                    'text' => $r->response,
                    'date' => $r->created_at->format('d M Y, h:i A'),
                ];
            }),
            'history' => $complaint->statusHistories->map(function ($history) {
                return [
                    'status' => $history->status,
                    'user' => optional($history->user)->name ?? 'Unknown',
                    'date' => $history->created_at->format('d M Y, h:i A'),
                ];
            }),
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved',
        ]);

        $complaint = Complaint::findOrFail($id);

        $complaint->status = $request->status;
        $complaint->save();

        ComplaintStatusHistory::create([
            'complaint_id' => $complaint->id,
            'status' => $request->status,
            'user_id' => auth()->id(),
        ]);

        Log::create([
            'action' => 'Status Updated',
            'description' => 'Complaint ID '.$complaint->id.' status changed to '.$request->status.' by user ID '.auth()->id(),
        ]);

        return response()->json([
            'message' => 'Status updated successfully',
            'new_status' => $complaint->status,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function downloadPdf(Request $request)
    {
        ini_set('max_execution_time', 300);

        $limit = $request->input('limit', '50');

        $complaints = $limit === 'all'
            ? Complaint::latest()->get()
            : Complaint::latest()->take((int) $limit)->get();

        $pdf = Pdf::loadView('admin.pdf_complaints', compact('complaints'));

        return $pdf->download('complaints.pdf');
    }

    public function exportCsv(Request $request)
    {
        $fileName = 'complaints.csv';
        $limit = $request->input('limit');

        // Fetch complaints based on selected limit
        if ($limit === 'all') {
            $complaints = \App\Models\Complaint::with(['user', 'department'])->latest()->get();
        } else {
            $complaints = \App\Models\Complaint::with(['user', 'department'])->latest()->take((int) $limit)->get();
        }

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate',
            'Expires' => '0',
        ];

        $callback = function () use ($complaints) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, ['ID', 'Student', 'Department', 'Title', 'Status', 'Created At']);

            // Complaint rows
            foreach ($complaints as $complaint) {
                fputcsv($file, [
                    $complaint->id,
                    $complaint->user->name ?? 'N/A',
                    $complaint->department->name ?? 'N/A',
                    $complaint->title,
                    ucfirst($complaint->status),
                    $complaint->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function logs()
    {
        $logs = Log::latest()->paginate(20);

        return view('admin.logs', compact('logs'));
    }

    public function sendReportEmail(Request $request)
    {
        $request->validate([
            'limit' => 'required',
        ]);

        GenerateReportJob::dispatch($request->input('limit'), auth()->user()->email);

        return back()->with('success', 'The report will be emailed shortly once generated.');
    }

    
public function sendEmailReport(Request $request)
{
    try {
        $limit = $request->get('limit', 10);

        // Eager load relationships with null checks
        $query = Complaint::with([
            'user' => function($query) {
                $query->withDefault([
                    'name' => 'Unknown Student'
                ]);
            },
            'department' => function($query) {
                $query->withDefault([
                    'name' => 'Unknown Department'
                ]);
            }
        ])->latest();

        if ($limit !== 'all') {
            $query->take((int) $limit);
        }

        $complaints = $query->get();

        if ($complaints->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No complaints found to generate report.'
            ]);
        }

        // Generate PDF with safe data
        $pdf = PDF::loadView('admin.pdf_complaints', [
            'complaints' => $complaints->map(function($complaint) {
                return [
                    'id' => $complaint->id,
                    'student' => $complaint->user->name,
                    'department' => $complaint->department->name,
                    'title' => $complaint->title,
                    'status' => $complaint->status,
                    'created_at' => $complaint->created_at->format('Y-m-d H:i')
                ];
            })
        ]);

        // Ensure storage directory exists
        $pdfPath = storage_path('app/public/email_complaint_report.pdf');
        if (!file_exists(dirname($pdfPath))) {
            mkdir(dirname($pdfPath), 0755, true);
        }

        $pdf->save($pdfPath);

        // Verify PDF was created
        if (!file_exists($pdfPath)) {
            throw new \Exception("Failed to generate PDF file");
        }

        // Get recipient email
        $recipientEmail = auth()->check() ? auth()->user()->email : 'admin@example.com';

        // Send email
        Mail::to($recipientEmail)->send(new ComplaintReportMail($pdfPath));

        return response()->json([
            'success' => true, 
            'message' => 'Report sent to ' . $recipientEmail
        ]);

    } catch (\Exception $e) {
        \Log::error('Email report failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Failed to send email: ' . $e->getMessage()
        ], 500);
    }
}


public function requestCsvExport(Request $request)
{
    $request->validate([
        'limit' => 'required', 
        'email' => 'required|email'
    ]);

    $adminEmail = auth()->user()->email;

    GenerateCsvAndSendLink::dispatch($request->limit, $adminEmail);

    return response()->json(['message' => 'Export started. You will receive a download link by email.']);
}


public function sendReportLink(Request $request)
{
    $limit = $request->query('limit', 'all');
    $email = auth()->user()->email;

    GenerateCsvAndSendLink::dispatch($email, $limit);

    return response()->json(['message' => 'CSV generation started. You will receive an email shortly.']);
}


public function downloads(Request $request)
{
    return view('admin.downloads');
}

}
