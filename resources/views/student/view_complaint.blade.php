{{--student/view_complaint--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                        },
                        secondary: {
                            100: '#f0f9ff',
                            200: '#e0f2fe',
                        },
                        dark: {
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                        neon: {
                            green: '#00ff9d',
                            blue: '#00f7ff',
                            pink: '#ff6ec7',
                            purple: '#b57edc'
                        }
                    },
                    boxShadow: {
                        'glow': '0 0 20px rgba(0, 255, 157, 0.4)',
                        'glow-sm': '0 0 10px rgba(0, 255, 157, 0.3)',
                        'inner-glow': 'inset 0 0 10px rgba(255, 255, 255, 0.1)',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'fade-in': 'fadeIn 0.5s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-dark-900 to-dark-800 min-h-screen relative">
    <!-- Animated background elements -->
    <div class="fixed inset-0 overflow-hidden -z-10">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-green-500/10 blur-3xl animate-float"></div>
        <div class="absolute bottom-1/3 right-1/4 w-72 h-72 rounded-full bg-teal-500/10 blur-3xl animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 w-60 h-60 rounded-full bg-emerald-500/10 blur-3xl animate-float" style="animation-delay: 4s;"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==')]"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-8 max-w-4xl">
        <!-- Top Navigation Buttons -->
        <div class="flex justify-between items-center mb-6">
            <a href="/dashboard/student" class="inline-flex items-center bg-white/10 border border-white/20 text-white font-bold py-2 px-4 rounded-lg hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-glow-sm">
                <i class="fas fa-arrow-left mr-2"></i> BACK TO DASHBOARD
            </a>
            <a href="/logout" class="inline-flex items-center bg-red-500/20 border border-red-500/30 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-500/30 transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-red-500/40">
                <i class="fas fa-sign-out-alt mr-2"></i> LOGOUT
            </a>
        </div>

        <!-- Complaint Details Card -->
        <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-xl shadow-2xl overflow-hidden mb-8 animate-fade-in">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6 relative overflow-hidden shadow-inner-glow">
                <h2 class="text-2xl font-bold text-white">
                    <i class="fas fa-file-alt mr-2"></i> COMPLAINT DETAILS
                </h2>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="space-y-4">
                    <div>
                        <h5 class="text-sm font-medium text-gray-400">TITLE</h5>
                        <p class="mt-1 text-lg">{{ $complaint->title }}</p>
                    </div>
                    
                    <div>
                        <h5 class="text-sm font-medium text-gray-400">DESCRIPTION</h5>
                        <p class="mt-1 bg-white/5 p-4 rounded-lg">{{ $complaint->description }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <h5 class="text-sm font-medium text-gray-400">DEPARTMENT</h5>
                            <p class="mt-1">{{ $complaint->department->name }}</p>
                        </div>
                        
                        <div>
                            <h5 class="text-sm font-medium text-gray-400">STATUS</h5>
                            <span class="mt-1 inline-block px-3 py-1 text-sm rounded-full font-semibold 
                                {{ $complaint->status == 'pending' ? 'bg-amber-500/20 text-amber-400' : 
                                   ($complaint->status == 'resolved' ? 'bg-green-500/20 text-green-400' : 'bg-blue-500/20 text-blue-400') }}">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        </div>
                        
                        @if($complaint->attachment)
                        <div>
                            <h5 class="text-sm font-medium text-gray-400">ATTACHMENT</h5>
                            <a href="{{ asset('storage/' . $complaint->attachment) }}" target="_blank" class="mt-1 inline-flex items-center text-neon-blue hover:text-neon-green transition-colors">
                                <i class="fas fa-file-download mr-2"></i> Download
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Responses Section -->
        {{-- <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-xl shadow-2xl overflow-hidden animate-fade-in" style="animation-delay: 0.2s">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-4 relative overflow-hidden shadow-inner-glow">
                <h3 class="text-lg font-bold text-white">
                    <i class="fas fa-comments mr-2"></i> RESPONSES
                </h3>
            </div>
            
            <div class="p-6">
                @forelse($complaint->responses as $response)
                <div class="mb-4 p-4 bg-white/5 border border-white/10 rounded-lg backdrop-blur-sm">
                    @if($response->admin_id)
                        <p class="font-semibold text-neon-blue"><i class="fas fa-user-shield mr-2"></i> ADMIN RESPONSE</p>
                    @elseif($response->department_id)
                        <p class="font-semibold text-neon-purple"><i class="fas fa-building mr-2"></i> DEPARTMENT RESPONSE</p>
                    @else
                        <p class="font-semibold text-gray-400"><i class="fas fa-user mr-2"></i> RESPONSE</p>
                    @endif
                    <p class="mt-2 bg-white/5 p-3 rounded-lg">{{ $response->response }}</p>
                    <small class="text-gray-500 block mt-2 text-sm">{{ $response->created_at->format('d M Y, h:i A') }}</small>
                </div>
                @empty
                <div class="text-center text-gray-400 py-6">
                    No responses yet for this complaint.
                </div>
                @endforelse
            </div>
        </div> --}}
    </div>

    <!-- Floating particles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-1/3 left-1/4 w-2 h-2 rounded-full bg-neon-green animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-1/4 right-1/3 w-3 h-3 rounded-full bg-neon-blue animate-float" style="animation-delay: 1.5s;"></div>
        <div class="absolute top-1/2 right-1/4 w-2 h-2 rounded-full bg-neon-pink animate-float" style="animation-delay: 2s;"></div>
    </div>
</body>
</html>