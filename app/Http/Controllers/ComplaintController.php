<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Department;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function showForm()
    {
        $departments = Department::all();

        return view('student.complaints.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'nullable|file|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('complaints');
        }

        $complaint = Complaint::create([
            'user_id' => Auth::id(),
            'department_id' => $request->department_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
        ]);

        Log::create([
            'action' => 'Complaint Submitted',
            'description' => 'Complaint ID '.$complaint->id.' submitted by user ID '.Auth::id(),
        ]);

        return redirect('/dashboard/student')->with('success', 'Complaint submitted successfully!');
    }
}
