<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - College Complaint System</title>
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
            <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg nav-link">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="/student/complaint/create" class="flex items-center space-x-3 px-4 py-3 rounded-lg nav-link active">
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
            <h1 class="text-xl font-bold">Submit Complaint</h1>
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
                        <h1 class="text-3xl font-bold text-white">Submit New Complaint</h1>
                        <p class="text-blue-100">Fill out the form to submit your complaint</p>
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

            <!-- Main Form Card -->
            <div class="card-bg rounded-xl shadow overflow-hidden animate-fade-in">
                <!-- Form Section -->
                <div class="p-6">
                    <form method="POST" action="/student/complaint/store" enctype="multipart/form-data">
                        @csrf
                        <!-- Department Field -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-slate-700 mb-2">DEPARTMENT</label>
                            <div class="relative">
                                <select name="department_id" class="w-full bg-white border border-slate-300 text-slate-700 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-slate-500"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Title Field -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-slate-700 mb-2">TITLE</label>
                            <div class="relative">
                                <input type="text" name="title" class="w-full bg-white border border-slate-300 text-slate-700 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-heading text-slate-500"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-slate-700 mb-2">DESCRIPTION</label>
                            <div class="relative">
                                <textarea name="description" class="w-full bg-white border border-slate-300 text-slate-700 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="4" required></textarea>
                            </div>
                        </div>

                        <!-- Attachment Field (Optional) -->
                        {{-- <div class="mb-5">
                            <label class="block text-sm font-medium text-slate-700 mb-2">ATTACH FILE (OPTIONAL)</label>
                            <div class="relative">
                                <input type="file" name="file" class="w-full bg-white border border-slate-300 text-slate-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div> --}}

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition-all duration-300 shadow hover:shadow-md mt-4">
                            SUBMIT COMPLAINT
                        </button>
                    </form>
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