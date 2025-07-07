<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\ComplaintStatusHistory;
use App\Models\Department;
use App\Models\Log;
use Illuminate\Http\Request;

class CleaningController extends Controller
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

        return view('cleaning.dashboard', compact('complaints', 'departments', 'selectedDeptId'));
    }

    public function showComplaint($id)
    {
        $complaint = Complaint::with(['user', 'department', 'responses', 'statusHistories.user'])->findOrFail($id);

        return view('cleaning.view_complaint', compact('complaint'));
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
            return redirect()->route('cleaning.dashboard')
                ->with('success', 'This complaint is already marked as resolved. You cannot update it again.');
        }

        $complaint->status = 'resolved';
        $complaint->save();

        ComplaintStatusHistory::create([
            'complaint_id' => $complaint->id,
            'status' => 'resolved',
            'user_id' => auth()->id(),
        ]);
        Log::create([
            'action' => 'status updated',
            'description' => 'Complaint ID '.$complaint->id.' updated by user ID '.auth()->id(),
        ]);

        return redirect()->route('cleaning.dashboard')
            ->with('success', 'Status updated successfully.');
    }
}
