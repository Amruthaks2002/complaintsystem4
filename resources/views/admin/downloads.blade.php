<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloads - College Complaint System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body { background-color: #f8fafc; color: #1e293b; min-height: 100vh; }
        .card-bg { background-color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #ddd; }
        .header-bg { background-color: #1d4ed8; color: white; }
        .sidebar-bg { background-color: #1e3a8a; color: white; }
        .nav-link { display: flex; align-items: center; padding: 0.75rem 1rem; border-radius: 0.5rem; transition: 0.2s; }
        .nav-link:hover { background-color: rgba(255,255,255,0.1); }
        .nav-link.active { background-color: rgba(255,255,255,0.2); }
        .sidebar { transition: all 0.3s ease; }
        @media (max-width: 768px) {
            .sidebar { position: fixed; left: -100%; top: 0; height: 100vh; z-index: 40; }
            .sidebar.open { left: 0; }
            .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 30; }
            .sidebar-overlay.open { display: block; }
        }
    </style>
</head>
<body class="min-h-screen flex">

    <!-- Sidebar Overlay for mobile -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar sidebar-bg w-64 min-h-screen hidden md:block">
        <div class="p-4 flex items-center gap-3 border-b border-blue-800">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <h2 class="font-bold">Admin Panel</h2>
                <p class="text-xs text-blue-200">College Complaint System</p>
            </div>
        </div>
        <nav class="p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-5 text-center"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.downloads') }}" class="nav-link {{ request()->is('admin/downloads') ? 'active' : '' }}">
                <i class="fas fa-download w-5 text-center"></i> <span>Downloads</span>
            </a>
            <a href="{{ route('admin.logs') }}" class="nav-link {{ request()->is('admin/logs') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list w-5 text-center"></i> <span>View Logs</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full nav-link text-left">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i> <span>Logout</span>
                </button>
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-x-hidden">
        <!-- Mobile Header -->
        <div class="md:hidden flex justify-between items-center p-4 bg-blue-800 text-white">
            <button id="sidebarToggle" class="text-white"><i class="fas fa-bars text-xl"></i></button>
            <h1 class="text-xl font-bold">Downloads</h1>
            <div class="w-6"></div>
        </div>

        <!-- Page Content -->
        <div class="container mx-auto px-4 py-6 max-w-5xl">
            <div class="mb-8 flex justify-between items-center header-bg rounded-xl p-6 shadow-md hidden md:flex">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white text-blue-700 rounded-full flex items-center justify-center shadow">
                        <i class="fas fa-download text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Download Reports</h1>
                        <p class="text-blue-100">Export complaint data in various formats</p>
                    </div>
                </div>
            </div>

            <!-- Download Form -->
<div class="card-bg p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4 text-slate-800">Choose Export Option</h2>

    <p class="text-sm text-red-600 mb-4">
        <i class="fas fa-triangle-exclamation mr-2"></i>
        <strong>Note:</strong> For large exports (above 1000 complaints), it is recommended to use the <strong>"Send to Email"</strong> option.
    </p>


                <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <label for="limit" class="font-medium">Select number of complaints:</label>

                    <select name="limit" id="limit" class="bg-white text-blue-900 px-3 py-2 rounded border border-slate-300">
                        <option value="10">Last 10</option>
                        <option value="50">Last 50</option>
                        <option value="100">Last 100</option>
                        <option value="500">Last 500</option>
                        <option value="1000">Last 1000</option>
                        <option value="all">All</option>
                    </select>

                    <button type="submit" formaction="{{ route('admin.download.pdf') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-medium">
                        Download PDF
                    </button>

                    <button type="submit" formaction="{{ route('admin.export.csv') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-medium">
                        Download Excel
                    </button>

                    <button type="button" onclick="sendEmail()" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 font-medium">
                        Send to Email
                    </button>
                </form>

                <!-- Result Message -->
                <div id="emailMessage" class="text-sm mt-3 font-medium"></div>
            </div>
        </div>
    </div>

    <!-- JS for sidebar toggle -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggle = document.getElementById('sidebarToggle');

        sidebarToggle?.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('open');
        });

        sidebarOverlay?.addEventListener('click', () => {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('open');
        });
    </script>

    <!-- Email Export Script -->
    <script>
        function sendEmail() {
            const limit = document.getElementById('limit').value;
            const emailMessage = document.getElementById('emailMessage');
            emailMessage.textContent = "Sending report to email...";
            emailMessage.className = "text-sm text-blue-700 mt-3 font-medium";

            fetch(`{{ route('admin.download.email') }}?limit=${limit}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed to send email');
                emailMessage.textContent = data.message;
                emailMessage.className = "text-sm text-green-700 mt-3 font-medium";
            })
            .catch(err => {
                emailMessage.textContent = err.message || "Error occurred while sending email.";
                emailMessage.className = "text-sm text-red-700 mt-3 font-medium";
                console.error(err);
            });
        }
    </script>

</body>
</html>
