<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaint - College Complaint System</title>
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
        /* Status Colors */
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
            <a href="{{ route('cleaning.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg nav-link">
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
            <h1 class="text-xl font-bold">Complaint Details</h1>
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
                        <h1 class="text-3xl font-bold text-white">Complaint Details</h1>
                        <p class="text-blue-100">Cleaner Dashboard</p>
                    </div>
                </div>
                <a href="{{ url('/cleaning/dashboard') }}" class="bg-white text-blue-700 px-5 py-2 rounded hover:bg-blue-50 font-medium transition-colors shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                </a>
            </div>

            <!-- Complaint Details -->
            <div class="card-bg rounded-xl shadow overflow-hidden mb-8 p-6 animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-800 mb-4">Complaint Information</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-slate-500">Title</p>
                                <p class="text-slate-800">{{ $complaint->title }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Student</p>
                                <p class="text-slate-800">{{ $complaint->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Department</p>
                                <p class="text-slate-800">{{ $complaint->department->name }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-slate-800 mb-4">Status Information</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-slate-500">Status</p>
                                <p class="text-slate-800">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium status-{{ str_replace(' ', '-', $complaint->status) }}">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Submitted</p>
                                <p class="text-slate-800">{{ $complaint->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Description</h3>
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <p class="text-slate-800">{{ $complaint->description }}</p>
                    </div>
                </div>

                @if($complaint->attachment)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Attachment</h3>
                    <a href="{{ asset('storage/' . $complaint->attachment) }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center">
                        <i class="fas fa-paperclip mr-2"></i> View File
                    </a>
                </div>
                @endif
            </div>

            <!-- Mark as Resolved Section -->
            <div class="card-bg rounded-xl shadow overflow-hidden mb-8 p-6 animate-fade-in">
                @if ($complaint->status === 'resolved')
                    <p class="text-green-600 font-semibold">
                        <i class="fas fa-check-circle mr-2"></i> This complaint is already marked as resolved. You cannot change it.
                    </p>
                @else
                    <form method="POST" action="{{ url('/cleaner/status/' . $complaint->id) }}">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg">
                            <i class="fas fa-check-circle mr-2"></i> Mark as Resolved
                        </button>
                    </form>
                @endif
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