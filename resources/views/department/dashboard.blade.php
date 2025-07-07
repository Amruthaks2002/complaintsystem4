<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Dashboard - College Complaint System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
        }
        .card-bg {
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(221, 221, 221, 0.5);
        }
        .header-bg {
            background-color: #1d4ed8;
            color: white;
        }
        .table-row-hover:hover {
            background-color: #f1f5f9;
        }
        .status-pending {
            background-color: #ffedd5;
            color: #9a3412;
            border: 1px solid #fed7aa;
        }
        .status-in-progress {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        .status-resolved {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-200 rounded-lg text-green-700 font-medium animate-fade-in">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Header Section -->
        <div class="mb-8 flex justify-between items-center header-bg rounded-xl p-6 shadow-md">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full bg-white text-blue-700 flex items-center justify-center shadow-sm">
                    <i class="fas fa-chalkboard-teacher text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Department Dashboard</h1>
                    <div class="text-blue-100">
                        <p class="text-sm"><span class="font-medium">Name:</span> {{ auth()->user()->name }}</p>
                        <p class="text-sm"><span class="font-medium">Role:</span> {{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-white text-blue-700 px-5 py-2 rounded hover:bg-blue-50 font-medium transition-colors shadow-sm">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>

        <!-- Complaints Section -->
        <div class="card-bg rounded-xl shadow overflow-hidden mb-8 animate-fade-in">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-slate-800">
                        <i class="fas fa-clipboard-list mr-2 text-blue-600"></i> Department Complaints
                    </h2>
                    <form method="GET" action="{{ url('/dashboard/department') }}" class="flex items-center space-x-2">
                        <label class="text-slate-700">Filter:</label>
                        <select name="department_id" onchange="this.form.submit()" class="bg-white text-slate-700 px-3 py-1 rounded border border-slate-300">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ (isset($selectedDeptId) && $selectedDeptId == $dept->id) ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-700 border-b border-gray-300">
                                <th class="p-3">#</th>
                                <th class="p-3">Student</th>
                                <th class="p-3">Title</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Submitted</th>
                                <th class="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($complaints as $complaint)
                            <tr class="border-b border-gray-200 table-row-hover">
                                <td class="p-3">{{ $loop->iteration + ($complaints->currentPage() - 1) * $complaints->perPage() }}</td>
                                <td class="p-3">{{ $complaint->user->name }}</td>
                                <td class="p-3">{{ $complaint->title }}</td>
                                <td class="p-3 capitalize">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $complaint->status == 'pending' ? 'status-pending' : 
                                           ($complaint->status == 'in progress' ? 'status-in-progress' : 'status-resolved') }}">
                                        {{ $complaint->status }}
                                    </span>
                                </td>
                                <td class="p-3">{{ $complaint->created_at->format('d M Y, h:i A') }}</td>
                                <td class="p-3">
                                    <a href="/department/complaint/{{ $complaint->id }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm font-medium">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-gray-600">
                                    <i class="fas fa-inbox text-4xl mb-4 text-blue-400"></i>
                                    <p>No complaints found for your department.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 pb-6">
        {{ $complaints->appends(request()->query())->links() }}
    </div>
            </div>
        </div>
    </div>
</body>
</html>