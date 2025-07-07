<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleaner Dashboard - College Complaint System</title>
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
        .sidebar-bg {
            background-color: #1e3a8a;
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
        .pagination {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 1.5rem;
        }
        .nav-link {
            transition: all 0.2s ease;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .sidebar {
            transition: all 0.3s ease;
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                z-index: 40;
                height: 100vh;
            }
            .sidebar.open {
                left: 0;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 30;
            }
            .sidebar-overlay.open {
                display: block;
            }
        }
    </style>
</head>
<body class="min-h-screen flex">
    <!-- Sidebar Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- Sidebar Navigation -->
    <div id="sidebar" class="sidebar sidebar-bg w-64 min-h-screen flex-shrink-0 hidden md:block">
        <div class="p-4 flex items-center space-x-3 border-b border-blue-800">
            <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center">
                <i class="fas fa-broom"></i>
            </div>
            <div>
                <h2 class="font-bold">Cleaner Panel</h2>
                <p class="text-xs text-blue-200">College Complaint System</p>
            </div>
        </div>
        
        <nav class="p-4 space-y-1">
            <a href="{{ route('cleaning.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg nav-link active">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span>Dashboard</span>
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg nav-link text-left">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span>Logout</span>
                </button>
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-x-hidden">
        <!-- Mobile Header -->
        <div class="md:hidden flex justify-between items-center p-4 bg-blue-800 text-white">
            <button id="sidebarToggle" class="text-white">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-bold">Cleaner Dashboard</h1>
            <div class="w-6"></div> <!-- Spacer for alignment -->
        </div>

        <!-- Main Content Area -->
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center header-bg rounded-xl p-6 shadow-md hidden md:flex">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-white text-blue-700 flex items-center justify-center shadow-sm">
                        <i class="fas fa-broom text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Cleaner Dashboard</h1>
                        <p class="text-blue-100">Manage cleaning complaints</p>
                    </div>
                </div>
                <div class="text-blue-100">
                    <p class="text-right">Welcome, {{ auth()->user()->name }}</p>
                    <p class="text-sm">{{ auth()->user()->email }}</p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg animate-fade-in">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Complaints Table -->
            <div class="card-bg rounded-xl shadow overflow-hidden mb-8 animate-fade-in">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">
                            <i class="fas fa-clipboard-list mr-2"></i> Complaints
                        </h2>
                        <form method="GET" action="{{ route('cleaning.dashboard') }}" class="flex items-center space-x-2">

                            <select name="department_id" onchange="this.form.submit()" class="bg-white border rounded px-3 py-1">
                                <option value="">All Departments</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ (isset($selectedDeptId) && $selectedDeptId == $dept->id) ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                <div class="p-6">
                    @if ($complaints->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-slate-700 border-b border-gray-300">
                                    <th class="p-3">#</th>
                                    <th class="p-3">Title</th>
                                    <th class="p-3">Student</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Date</th>
                                    <th class="p-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($complaints as $complaint)
                                <tr class="border-b border-gray-200 table-row-hover">
                                    <td class="p-3">{{ $loop->iteration + ($complaints->currentPage() - 1) * $complaints->perPage() }}</td>
                                    <td class="p-3">{{ $complaint->title }}</td>
                                    <td class="p-3">{{ $complaint->user->name }}</td>
                                    <td class="p-3 capitalize">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium 
                                            {{ $complaint->status == 'pending' ? 'status-pending' : 
                                               ($complaint->status == 'in progress' ? 'status-in-progress' : 'status-resolved') }}">
                                            {{ $complaint->status }}
                                        </span>
                                    </td>
                                    <td class="p-3">{{ $complaint->created_at->format('d M Y') }}</td>
                                    <td class="p-3">
                                        <a href="{{ url('/cleaner/complaint/' . $complaint->id) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $complaints->appends(request()->query())->links() }}</div>
                    </div>
                    @else
                        <div class="text-center py-8 text-gray-600">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p>No complaints found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    // Sidebar toggle for mobile
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        sidebarOverlay.classList.toggle('open');
    });
    
    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        sidebarOverlay.classList.remove('open');
    });
    </script>
</body>
</html> 