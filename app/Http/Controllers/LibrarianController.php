<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\Department;
use Illuminate\Http\Request;

class LibrarianController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::all();
        $selectedDeptId = $request->input('department_id');

        $query = Complaint::with(['user', 'department'])->latest();

        if ($selectedDeptId) {
            $query->where('department_id', $selectedDeptId);
        }

        $complaints = $query->paginate(10);

        return view('librarian.dashboard', compact('complaints', 'departments', 'selectedDeptId'));
    }

    public function showComplaint($id)
    {
        $complaint = Complaint::with(['user', 'department', 'responses'])->findOrFail($id);

        return view('librarian.view_complaint', compact('complaint'));
    }

    public function respond(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|string',
        ]);

        ComplaintResponse::create([
            'complaint_id' => $id,
            'response' => $request->input('response'),
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Response submitted successfully!');
    }

    public function updateStatus($id)
    {
        $complaint = Complaint::findOrFail($id);

        if ($complaint->status === 'resolved') {
            return redirect()->route('librarian.dashboard')
                ->with('success', 'This complaint is already resolved.');
        }

        $complaint->status = 'resolved';
        $complaint->save();

        return redirect()->route('librarian.dashboard')
            ->with('success', 'Status updated.');
    }
}
