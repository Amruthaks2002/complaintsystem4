<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - College Complaint System</title>
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
    <!-- Sidebar Overlay (Mobile Only) -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- Sidebar Navigation -->
    <div id="sidebar" class="sidebar sidebar-bg w-64 min-h-screen flex-shrink-0 hidden md:block">
        <div class="p-4 flex items-center space-x-3 border-b border-blue-800">
            <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <h2 class="font-bold">Student Panel</h2>
                <p class="text-xs text-blue-200">College Complaint System</p>
            </div>
        </div>
        
        <nav class="p-4 space-y-1">
            <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg nav-link {{ request()->is('student/dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="/student/complaint/create" class="flex items-center space-x-3 px-4 py-3 rounded-lg nav-link {{ request()->is('student/complaint/create') ? 'active' : '' }}">
                <i class="fas fa-plus w-5 text-center"></i>
                <span>Submit New Complaint</span>
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
            <h1 class="text-xl font-bold">Student Dashboard</h1>
            <div class="w-6"></div> <!-- Spacer for alignment -->
        </div>

        <!-- Main Content Area -->
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center header-bg rounded-xl p-6 shadow-md hidden md:flex">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-white text-blue-700 flex items-center justify-center shadow-sm">
                        <i class="fas fa-comment-dots text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Student Complaint Portal</h1>
                        <p class="text-blue-100">Submit and track your complaints</p>
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
                            <i class="fas fa-clipboard-list mr-2"></i> Your Complaints
                        </h2>
                        <form method="GET" action="{{ route('student.dashboard') }}" class="flex flex-wrap items-center space-x-2">
    <select name="status" onchange="this.form.submit()" class="bg-white border rounded px-3 py-1">
        <option value="">All Statuses</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
    </select>

    <select name="department_id" onchange="this.form.submit()" class="bg-white border rounded px-3 py-1">
        <option value="">All Departments</option>
        @foreach ($departments as $dept)
            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
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
                                    <th class="p-3">Department</th>
                                    <th class="p-3">Title</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Date</th>
                                    <th class="p-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($complaints as $complaint)
                                <tr class="border-b border-gray-200 table-row-hover">
                                    <td class="p-3">{{ $loop->iteration + ($complaints->currentPage() - 1) * $complaints->perPage() }}</td>
                                    <td class="p-3">{{ $complaint->department->name }}</td>
                                    <td class="p-3">{{ $complaint->title }}</td>
                                    <td class="p-3 capitalize">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium 
                                            {{ $complaint->status == 'pending' ? 'status-pending' : 
                                               ($complaint->status == 'in progress' ? 'status-in-progress' : 'status-resolved') }}">
                                            {{ $complaint->status }}
                                        </span>
                                    </td>
                                    <td class="p-3">{{ $complaint->created_at->format('d M Y') }}</td>
                                    <td class="p-3">
                                        <button onclick="viewComplaint({{ $complaint->id }})" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
    <div class="text-sm text-gray-600">
        Showing {{ $complaints->firstItem() }} to {{ $complaints->lastItem() }} of {{ $complaints->total() }} complaints
    </div>

    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($complaints->onFirstPage())
            <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded cursor-not-allowed">Previous</span>
        @else
            <a href="{{ $complaints->previousPageUrl() }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Previous</a>
        @endif

        {{-- Next Page Link --}}
        @if ($complaints->hasMorePages())
            <a href="{{ $complaints->nextPageUrl() }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Next</a>
        @else
            <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded cursor-not-allowed">Next</span>
        @endif
    </div>
</div>

                    </div>
                    @else
                        <div class="text-center py-8 text-gray-600">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p>No complaints found.</p>
                            <a href="/student/complaint/create" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-medium">
                                Submit Your First Complaint
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Complaint Modal -->
    <div id="complaintModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white max-w-3xl w-full p-6 rounded-xl shadow-2xl border border-blue-100 overflow-y-auto max-h-[90vh] relative animate-fade-in">
            <button onclick="closeComplaintModal()" class="absolute top-4 right-4 text-gray-500 hover:text-blue-800">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div id="complaintDetails" class="space-y-4"></div>
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

    function viewComplaint(id) {
        fetch(`/student/complaint/${id}`)
            .then(res => res.json())
            .then(data => {
                let html = `
                    <div class="bg-blue-600 p-6 text-white rounded-t-lg">
                        <h2 class="text-xl font-bold">
                            <i class="fas fa-file-alt mr-2"></i> COMPLAINT DETAILS
                        </h2>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="space-y-4">
                            <div>
                                <h5 class="text-sm font-medium text-gray-500">TITLE</h5>
                                <p class="mt-1 text-lg">${data.title}</p>
                            </div>
                            
                            <div>
                                <h5 class="text-sm font-medium text-gray-500">DESCRIPTION</h5>
                                <p class="mt-1 bg-gray-50 p-4 rounded-lg">${data.description}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500">DEPARTMENT</h5>
                                    <p class="mt-1">${data.department}</p>
                                </div>
                                
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500">STATUS</h5>
                                    <span class="mt-1 inline-block px-3 py-1 text-sm rounded-full font-semibold 
                                        ${data.status == 'pending' ? 'status-pending' : 
                                           (data.status == 'resolved' ? 'status-resolved' : 'status-in-progress')}">
                                        ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                                    </span>
                                </div>`;

                if (data.attachment) {
                    html += `
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500">ATTACHMENT</h5>
                                    <a href="/storage/${data.attachment}" target="_blank" class="mt-1 inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-file-download mr-2"></i> Download
                                    </a>
                                </div>`;
                }

                html += `</div></div>`;

                document.getElementById('complaintDetails').innerHTML = html;
                document.getElementById('complaintModal').classList.remove('hidden');
                document.getElementById('complaintModal').classList.add('flex');
                document.body.style.overflow = 'hidden';
            })
            .catch(err => {
                console.error("Failed to load complaint:", err);
                alert("Failed to load complaint details.");
            });
    }

    function closeComplaintModal() {
        document.getElementById('complaintModal').classList.add('hidden');
        document.getElementById('complaintModal').classList.remove('flex');
        document.getElementById('complaintDetails').innerHTML = '';
        document.body.style.overflow = '';
    }
    </script>
</body>
</html>





