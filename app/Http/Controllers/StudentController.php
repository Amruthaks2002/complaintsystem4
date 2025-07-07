<?php

namespace App\Http\Controllers;

use App\Mail\ComplaintReceivedAdmin;
use App\Mail\ComplaintReceivedStudent;
use App\Models\Complaint;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class StudentController extends Controller
{
    public function showRegister()
    {
        return view('auth.student_register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        Auth::login($user);

        return redirect('/dashboard/student');
    }

    public function showLogin()
    {
        return view('auth.student_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (auth()->user()->role === 'student') {
                return redirect('/dashboard/student');
            } else {
                Auth::logout();

                return back()->withErrors(['email' => 'You are not authorized to login as student.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function dashboard(Request $request)
    {
        $query = auth()->user()->complaints()
            ->with('department')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->paginate(10);

        return view('student.dashboard', compact('complaints'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function viewComplaints()
    {
        $complaints = Complaint::with(['department', 'responses.user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('student.view_complaint', compact('complaints'));
    }

    public function viewComplaint($id)
    {
        $complaint = Complaint::with('department', 'responses')->findOrFail($id);

        return view('student.view_complaint', compact('complaint'));
    }

    public function submitComplaint(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'department_id' => 'required|exists:departments,id',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $data = $request->only('title', 'description', 'department_id');
        $data['user_id'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $complaint = Complaint::create($data);
        $complaint->load('user', 'department');

        // Send email to student
        // Mail::to($complaint->user->email)->send(new ComplaintReceivedStudent($complaint));

        // Send email to all admins and the relevant department head
        // $admins = User::where('role', 'admin')->pluck('email')->toArray();
        // $departmentHead = User::where('role', 'department_head')
        //                       ->where('department_id', $complaint->department_id)
        //                       ->pluck('email')->toArray();

        // $recipients = array_merge($admins, $departmentHead);
        // Mail::to($recipients)->send(new ComplaintReceivedAdmin($complaint));

        Log::create([
            'user_id' => auth()->id(),
            'action' => 'Submitted new complaint',
            'details' => 'Complaint ID: '.$complaint->id.', Title: '.$complaint->title,
        ]);

        return redirect()->back()->with('success', 'Complaint submitted successfully!');
    }

    public function show($id)
    {
        $complaint = Complaint::with(['department', 'responses' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // Check if the complaint belongs to the authenticated student
        if ($complaint->user_id != auth()->id()) {
            abort(403);
        }

        $responseData = $complaint->responses->map(function ($response) {
            return [
                'response' => $response->response,
                'date' => $response->created_at->format('d M Y, h:i A'),
                'admin_id' => $response->admin_id,
                'department_id' => $response->department_id,
            ];
        });

        return response()->json([
            'title' => $complaint->title,
            'description' => $complaint->description,
            'department' => $complaint->department->name,
            'status' => $complaint->status,
            'attachment' => $complaint->attachment,
            'responses' => $responseData,
        ]);
    }
}
