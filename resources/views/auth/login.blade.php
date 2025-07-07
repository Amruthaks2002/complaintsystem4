<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - College Complaint System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        college: {
                            blue: '#003366',
                            gold: '#FFD700',
                            accent: '#E74C3C',
                            light: '#F5F7FA'
                        }
                    },
                    boxShadow: {
                        'glow': '0 0 20px rgba(0, 51, 102, 0.4)',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .college-bg {
            background-image: linear-gradient(rgba(0, 51, 102, 0.85), rgba(0, 51, 102, 0.85)), 
                              url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
    </style>
</head>
<body class="min-h-screen college-bg flex items-center justify-center p-4">

    <!-- Main Container -->
    <div class="w-full max-w-4xl flex overflow-hidden rounded-2xl shadow-2xl">
        <!-- Left Side - College Info -->
        <div class="hidden md:flex flex-1 bg-college-blue relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-t from-college-blue to-transparent opacity-90"></div>
            <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                 alt="College Campus" 
                 class="w-full h-full object-cover">
            
            <div class="absolute bottom-0 left-0 right-0 p-8 text-white z-10">
                <h2 class="text-2xl font-bold mb-2">Welcome to</h2>
                <h1 class="text-4xl font-bold mb-4">University Complaint Portal</h1>
                <p class="text-blue-200">Your voice matters. Submit and track complaints easily.</p>
            </div>
            
            <!-- Floating Elements -->
            <div class="absolute top-1/4 right-1/4 w-16 h-16 rounded-full bg-college-gold opacity-20 floating" style="animation-delay: 0.5s;"></div>
            <div class="absolute bottom-1/3 left-1/4 w-20 h-20 rounded-full bg-white opacity-10 floating" style="animation-delay: 1s;"></div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="w-full md:w-1/2 bg-white p-10 flex flex-col justify-center">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 mb-4 rounded-full bg-college-blue text-white shadow-lg">
                    <i class="fas fa-comments text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Student Login</h2>
                <p class="text-gray-600">Access your complaint dashboard</p>
            </div>
            
            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ url('/login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" id="email" name="email" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-college-blue focus:border-college-blue focus:outline-none transition" placeholder="student@university.edu" required>
                    </div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-college-blue focus:border-college-blue focus:outline-none transition" placeholder="••••••••" required>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-college-blue focus:ring-college-blue border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    
                    <div class="text-sm">
                        <a href="#" class="font-medium text-college-blue hover:text-college-accent">Forgot password?</a>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-college-blue hover:bg-college-accent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-college-blue transition transform hover:-translate-y-0.5">
                        Sign In
                    </button>
                </div>
            </form>
            
            <div class="mt-8 text-center text-sm text-gray-600">
                <p>Don't have an account? <a href="#" class="font-medium text-college-blue hover:text-college-accent">Contact admin</a></p>
            </div>
            
            <div class="mt-6 border-t border-gray-200 pt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Quick Links</span>
                    </div>
                </div>
                
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <a href="#" class="text-sm text-center text-gray-600 hover:text-college-blue">
                        <i class="fas fa-home mr-1"></i> College Home
                    </a>
                    <a href="#" class="text-sm text-center text-gray-600 hover:text-college-blue">
                        <i class="fas fa-question-circle mr-1"></i> Help Desk
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>