<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Details - College Complaint System</title>
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

        <!-- Complaint Details Card -->
        <div class="card-bg rounded-xl shadow overflow-hidden mb-8 animate-fade-in">
            <div class="p-6 border-b border-gray-200 bg-blue-600 text-white">
                <h2 class="text-xl font-bold">
                    <i class="fas fa-file-alt mr-2"></i> COMPLAINT DETAILS
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-600 font-medium">STUDENT:</p>
                        <p class="text-slate-800 font-medium">{{ $complaint->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 font-medium">DEPARTMENT:</p>
                        <p class="text-slate-800 font-medium">{{ $complaint->department->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 font-medium">STATUS:</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium mt-1 
                            {{ $complaint->status == 'pending' ? 'status-pending' : 
                               ($complaint->status == 'in progress' ? 'status-in-progress' : 'status-resolved') }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 font-medium">SUBMITTED AT:</p>
                        <p class="text-slate-800 font-medium">{{ $complaint->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-slate-600 font-medium">TITLE:</p>
                    <p class="text-slate-800 text-lg font-bold mt-1">{{ $complaint->title }}</p>
                </div>

                <div>
                    <p class="text-sm text-slate-600 font-medium">DESCRIPTION:</p>
                    <div class="mt-2 bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <p class="text-slate-700">{{ $complaint->description }}</p>
                    </div>
                </div>

                @if($complaint->attachment)
                    <div>
                        <p class="text-sm text-slate-600 font-medium">ATTACHMENT:</p>
                        <a href="{{ asset('storage/' . $complaint->attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors duration-200 font-medium">
                            <i class="fas fa-file-download mr-1"></i> View File
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @if(auth()->user()->role === 'department_head')
            <!-- Response Form -->
            <div class="card-bg rounded-xl shadow overflow-hidden mb-8 animate-fade-in">
                <div class="p-6 border-b border-gray-200 bg-blue-600 text-white">
                    <h2 class="text-xl font-bold">
                        <i class="fas fa-reply mr-2"></i> RESPOND TO COMPLAINT
                    </h2>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ url('/department/complaint/respond/' . $complaint->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="response" class="block text-sm font-medium text-slate-700 mb-2">YOUR RESPONSE:</label>
                            <textarea name="response" id="response" rows="4" class="w-full bg-white border border-slate-300 text-slate-700 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required></textarea>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white font-medium py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            SUBMIT RESPONSE
                        </button>
                    </form>
                </div>
            </div>
        @endif

        {{-- <!-- Responses Section -->
        <div class="card-bg rounded-xl shadow overflow-hidden animate-fade-in">
            <div class="p-6 border-b border-gray-200 bg-blue-600 text-white">
                <h2 class="text-xl font-bold">
                    <i class="fas fa-comments mr-2"></i> RESPONSES
                </h2>
            </div>
            <div class="p-6">
                @if($complaint->responses->count())
                    <div class="space-y-4">
                        @foreach($complaint->responses as $response)
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    @if($response->user)
                                        <div class="{{ $response->user->role === 'admin' ? 'text-blue-600 font-medium' : 'text-slate-700 font-medium' }}">
                                            <i class="{{ $response->user->role === 'admin' ? 'fas fa-user-shield' : 'fas fa-building' }} mr-2"></i>
                                            {{ ucfirst($response->user->role) }}
                                        </div>
                                    @else
                                        <div class="text-blue-600 font-medium">
                                            <i class="fas fa-user-shield mr-2"></i>
                                            Department
                                        </div>
                                    @endif
                                    <span class="text-slate-500 text-sm ml-auto">{{ $response->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                                <div class="mt-2 bg-white p-3 rounded-lg border border-slate-200">
                                    <p class="text-slate-700">{{ $response->response }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-comment-slash text-4xl mb-4 text-slate-400"></i>
                        <p class="text-slate-600">No responses yet.</p>
                    </div>
                @endif
            </div>
        </div> --}}

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="{{ url('/dashboard/department') }}" class="inline-block bg-blue-600 text-white font-medium py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> BACK TO DASHBOARD
            </a>
        </div>
    </div>
</body>
</html>