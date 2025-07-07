<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
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
<body class="bg-gradient-to-br from-dark-900 to-dark-800 min-h-screen flex items-center justify-center p-4 overflow-hidden">
    <!-- Animated background elements -->
    <div class="fixed inset-0 overflow-hidden -z-10">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 rounded-full bg-green-500/10 blur-3xl animate-float"></div>
        <div class="absolute bottom-1/3 right-1/4 w-72 h-72 rounded-full bg-teal-500/10 blur-3xl animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 w-60 h-60 rounded-full bg-emerald-500/10 blur-3xl animate-float" style="animation-delay: 4s;"></div>
        
        <!-- Grid pattern -->
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==')]"></div>
    </div>

    <!-- Main Card -->
    <div class="w-full max-w-md bg-white/5 backdrop-blur-lg border border-white/10 rounded-xl shadow-2xl overflow-hidden transition-all duration-500 hover:shadow-glow animate-fade-in">
        <!-- Header with subtle gradient -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-8 text-center relative overflow-hidden shadow-inner-glow">
            <!-- Decorative elements -->
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/5 rounded-full animate-pulse-slow"></div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/5 rounded-full animate-pulse-slow" style="animation-delay: 1s;"></div>
            
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-white/10 border border-white/20 shadow-inner-glow">
                    <i class="fas fa-user-graduate text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    Student Portal
                </h1>
                <p class="text-green-200/80">Enter your credentials to continue</p>
            </div>
        </div>
        
        <!-- Login Form -->
        <div class="p-6">
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login/student" class="space-y-5">
                @csrf
                <!-- Email Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-neon-green focus:border-transparent placeholder-gray-500" required autofocus>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-envelope text-gray-500"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Password Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-neon-green focus:border-transparent placeholder-gray-500" required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-lock text-gray-500"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-neon-green to-emerald-500 text-dark-900 font-bold py-3 px-4 rounded-xl hover:from-green-400 hover:to-emerald-400 transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-glow-sm">
                    LOGIN
                </button>
                
                <!-- Registration Link -->
                <div class="text-center pt-2">
                    <a href="/register/student" class="text-sm text-gray-400 hover:text-neon-green transition-colors">
                        New student? Register here
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Floating particles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/5 w-2 h-2 rounded-full bg-neon-green animate-float" style="animation-delay: 0.5s;"></div>
        <div class="absolute top-2/3 left-1/3 w-3 h-3 rounded-full bg-neon-blue animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 right-1/4 w-2 h-2 rounded-full bg-neon-pink animate-float" style="animation-delay: 1.5s;"></div>
    </div>
</body>
</html>