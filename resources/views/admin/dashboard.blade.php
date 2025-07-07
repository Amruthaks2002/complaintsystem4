<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - College Complaint System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { background-color: #f8fafc; color: #1e293b; min-height: 100vh; }
        .card-bg { background-color: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #ddd; }
        .header-bg { background-color: #1d4ed8; color: white; }
        .sidebar-bg { background-color: #1e3a8a; color: white; }
        .table-row-hover:hover { background-color: #f1f5f9; }
        .status-pending { background: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .status-resolved { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .pagination { display: flex; justify-content: center; margin-top: 1rem; gap: 0.5rem; }
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
    <!-- Sidebar Overlay -->
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
            <h1 class="text-xl font-bold">Complaint Dashboard</h1>
            <div class="w-6"></div>
        </div>

        <div class="container mx-auto px-4 py-6 max-w-7xl">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center header-bg rounded-xl p-6 shadow-md hidden md:flex">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white text-blue-700 rounded-full flex items-center justify-center shadow">
                        <i class="fas fa-comment-dots text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Complaint Dashboard</h1>
                        <p class="text-blue-100">Manage student complaints</p>
                        <!-- Request CSV Export -->
{{-- <div class="card-bg rounded-xl shadow mb-8">
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4 text-slate-800">Request CSV Export</h2>
        <form id="csvExportForm" class="flex flex-col md:flex-row items-center gap-4">
            <select name="limit" id="csvLimit" class="bg-white border border-slate-300 rounded px-4 py-2 text-slate-700">
                <option value="all">All Complaints</option>
                <option value="10">Last 10 Complaints</option>
                <option value="50">Last 50 Complaints</option>
                <option value="100">Last 100 Complaints</option>
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                Request Export to Email
            </button>
        </form>
        <p id="exportStatus" class="text-green-600 mt-3 hidden">Request sent! Check your email shortly.</p>
    </div>
</div> --}}

                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="card-bg rounded-xl shadow mb-8">
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-4">
    <div>
        <label class="text-slate-700">Filter by Status:</label>
        <select name="status" onchange="this.form.submit()" class="bg-white px-3 py-2 rounded border border-slate-300 text-slate-700">
            <option value="">All</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
        </select>
    </div>

    <div>
        <label class="text-slate-700">Filter by Department:</label>
        <select name="department" onchange="this.form.submit()" class="bg-white px-3 py-2 rounded border border-slate-300 text-slate-700">
            <option value="">All</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
    </div>
</form>

                </div>

                <!-- Complaints Table -->
                <div class="p-6">
                    @if ($complaints->count())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-slate-700 border-b border-gray-300">
                                        <th class="p-3">#</th>
                                        <th class="p-3">Student</th>
                                        <th class="p-3">Department</th>
                                        <th class="p-3">Title</th>
                                        <th class="p-3">Status</th>
                                        <th class="p-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($complaints as $complaint)
                                        <tr class="border-b border-gray-200 table-row-hover">
                                            <td class="p-3">{{ $loop->iteration + ($complaints->currentPage() - 1) * $complaints->perPage() }}</td>
                                            <td class="p-3">{{ $complaint->user->name }}</td>
                                            <td class="p-3">{{ $complaint->department->name }}</td>
                                            <td class="p-3">{{ $complaint->title }}</td>
                                            <td class="p-3 capitalize">
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium status-{{ str_replace(' ', '-', $complaint->status) }}">
                                                    {{ $complaint->status }}
                                                </span>
                                            </td>
                                            <td class="p-3">
                                                <button onclick="viewComplaint({{ $complaint->id }})" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4 flex justify-between items-center text-sm text-gray-700">
    <div>
        Showing 
        <span class="font-semibold">{{ $complaints->firstItem() }}</span>
        to 
        <span class="font-semibold">{{ $complaints->lastItem() }}</span>
        of 
        <span class="font-semibold">{{ $complaints->total() }}</span>
        complaints
    </div>
    <div class="flex items-center gap-2">
        @if ($complaints->onFirstPage())
            <span class="px-3 py-1 bg-gray-200 rounded text-gray-500">Previous</span>
        @else
            <a href="{{ $complaints->previousPageUrl() }}@if(request()->getQueryString())&{{ http_build_query(request()->except('page')) }}@endif"
               class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Previous</a>
        @endif

        @if ($complaints->hasMorePages())
            <a href="{{ $complaints->nextPageUrl() }}@if(request()->getQueryString())&{{ http_build_query(request()->except('page')) }}@endif"
               class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Next</a>
        @else
            <span class="px-3 py-1 bg-gray-200 rounded text-gray-500">Next</span>
        @endif
    </div>
</div>

</div>
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

    <!-- Modal -->
    <div id="complaintModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white max-w-3xl w-full p-6 rounded-xl shadow-2xl border border-blue-100 overflow-y-auto max-h-[90vh] relative">
            <button onclick="closeComplaintModal()" class="absolute top-4 right-4 text-gray-500 hover:text-blue-800">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div id="complaintDetails" class="space-y-4"></div>
        </div>
    </div>

    <script>
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
        fetch(`/admin/complaint/${id}`)
            .then(res => res.json())
            .then(data => {
                let html = `
                    <h2 class="text-xl font-bold mb-2">${data.title}</h2>
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div><p class="text-sm text-gray-500">Student</p><p>${data.student}</p></div>
                        <div><p class="text-sm text-gray-500">Department</p><p>${data.department}</p></div>
                    </div>
                    <div><p class="text-sm text-gray-500">Description</p><p class="bg-slate-100 p-3 rounded mt-1">${data.description}</p></div>`;

                if (data.file) {
                    html += `<div class="mt-3"><p class="text-sm text-gray-500">Attachment</p><a href="/storage/${data.file}" target="_blank" class="text-blue-600 hover:underline"><i class="fas fa-paperclip mr-2"></i>View File</a></div>`;
                }

                if (data.responses?.length) {
                    html += `<div class="mt-4"><h3 class="font-semibold mb-2">Responses</h3>`;
                    data.responses.forEach(r => {
                        html += `<div class="mb-3 border-t pt-2"><p><strong>${r.by}</strong>: ${r.text}</p><p class="text-sm text-gray-500">${r.date}</p></div>`;
                    });
                    html += `</div>`;
                }

                if (data.history?.length) {
                    html += `<div class="mt-4"><h3 class="font-semibold mb-2">Status History</h3>`;
                    data.history.forEach(h => {
                        html += `<p class="text-sm">Status changed to <strong>${h.status}</strong> by <strong>${h.user}</strong> on ${h.date}</p>`;
                    });
                    html += `</div>`;
                }

                html += `
                    <form id="statusForm" class="mt-6">
                        <label class="block text-sm font-medium mb-2">Update Status</label>
                        <select name="status" class="w-full border p-3 rounded mb-3" required>
                            <option value="pending" ${data.status === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="resolved" ${data.status === 'resolved' ? 'selected' : ''}>Resolved</option>
                        </select>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Update</button>
                    </form>`;

                document.getElementById('complaintDetails').innerHTML = html;
                document.getElementById('complaintModal').classList.remove('hidden');
                document.getElementById('complaintModal').classList.add('flex');
                document.body.style.overflow = 'hidden';

                document.getElementById('statusForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    fetch(`/admin/complaint/${data.id}/status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(resp => {
                        alert(resp.message);
                        location.reload();
                    })
                    .catch(() => alert("Failed to update status."));
                });
            });
    }

    function closeComplaintModal() {
        document.getElementById('complaintModal').classList.add('hidden');
        document.getElementById('complaintModal').classList.remove('flex');
        document.body.style.overflow = '';
    }
    </script>

    <script>
document.getElementById('csvExportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const limit = document.getElementById('csvLimit').value;

    fetch(`/admin/download/email?limit=${limit}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('exportStatus').classList.remove('hidden');
        document.getElementById('exportStatus').textContent = data.message;
    })
    .catch(err => {
        alert('Something went wrong. Please try again.');
        console.error(err);
    });
});
</script>

</body>
</html>
