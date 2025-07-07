<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function showLogin()
    {
        return view('auth.department_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (auth()->user()->role === 'department_head') {
                return redirect('/dashboard/department');
            } else {
                Auth::logout();

                return back()->with('error', 'Access denied');
            }
        }

        return back()->with('error', 'Invalid credentials');
    }

    public function showComplaint($id)
    {
        $complaint = Complaint::with('user', 'department')->findOrFail($id);

        return view('department.view', compact('complaint'));
    }

    public function submitResponse(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|string',
        ]);

        $data = [
            'complaint_id' => $id,
            'response' => $request->input('response'),
        ];

        if (auth()->user()->role === 'admin') {
            $data['admin_id'] = auth()->id();
        } elseif (auth()->user()->role === 'department_head') {
            $data['department_id'] = auth()->id();
        }

        ComplaintResponse::create($data);

        return back()->with('success', 'Response submitted successfully!');
    }

    public function dashboard(Request $request)
    {
        $departments = Department::all();
        $selectedDeptId = $request->input('department_id');

        $complaintsQuery = Complaint::with('user', 'department')->latest();

        if ($selectedDeptId) {
            $complaintsQuery->where('department_id', $selectedDeptId);
        }

        $complaints = $complaintsQuery->paginate(10);

        return view('department.dashboard', compact('complaints', 'departments', 'selectedDeptId'));
    }
}
