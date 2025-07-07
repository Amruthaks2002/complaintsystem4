<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Activity Logs</title>
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
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <h2 class="font-bold">Admin Panel</h2>
                <p class="text-xs text-blue-200">College Complaint System</p>
            </div>
        </div>
        
        <nav class="p-4 space-y-1">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg nav-link">
        <i class="fas fa-tachometer-alt w-5 text-center"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.logs') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg nav-link active">
        <i class="fas fa-clipboard-list w-5 text-center"></i>
        <span>View Logs</span>
    </a>

    <a href="{{ route('admin.downloads') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg nav-link">
        <i class="fas fa-download w-5 text-center"></i>
        <span>Downloads</span>
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
            <h1 class="text-xl font-bold">Activity Logs</h1>
            <div class="w-6"></div> <!-- Spacer for alignment -->
        </div>

        <!-- Main Content Area -->
        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center header-bg rounded-xl p-6 shadow-md hidden md:flex">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-white text-blue-700 flex items-center justify-center shadow-sm">
                        <i class="fas fa-clipboard-list text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Activity Logs</h1>
                        <p class="text-blue-100">System activity history</p>
                    </div>
                </div>
            </div>

            <!-- Logs Table -->
            <div class="card-bg rounded-xl shadow overflow-hidden">
                <div class="p-6">
                    @if ($logs->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-slate-700 border-b border-gray-300">
                                    <th class="p-3">#</th>
                                    <th class="p-3">Action</th>
                                    <th class="p-3">Description</th>
                                    <th class="p-3">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $index => $log)
                                <tr class="border-b border-gray-200 table-row-hover">
                                    <td class="p-3">{{ $index + $logs->firstItem() }}</td>
                                    <td class="p-3">{{ $log->action }}</td>
                                    <td class="p-3">{{ $log->description }}</td>
                                    <td class="p-3">{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $logs->links() }}</div>
                    </div>
                    @else
                        <div class="text-center py-8 text-gray-600">
                            <i class="fas fa-inbox text-4xl mb-4"></i>
                            <p>No activity logs found.</p>
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