<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - College Complaint System</title>
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
        .input-field {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.2s ease;
        }
        .input-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-md">
        <div class="header-bg rounded-xl p-6 shadow-md mb-8 text-center">
            <div class="w-16 h-16 rounded-full bg-white text-blue-700 flex items-center justify-center shadow-sm mx-auto mb-4">
                <i class="fas fa-user-graduate text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white">Student Registration</h1>
            <p class="text-blue-100 mt-2">Join our college complaint system</p>
        </div>

        <div class="card-bg rounded-xl shadow overflow-hidden p-8">
            <form method="POST" action="/register/student">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                    <input type="text" name="name" class="input-field" required placeholder="Enter your full name">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                    <input type="email" name="email" class="input-field" required placeholder="Enter your email">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                    <input type="password" name="password" class="input-field" required placeholder="Create a password">
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="input-field" required placeholder="Confirm your password">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 shadow-sm">
                    <i class="fas fa-user-plus mr-2"></i> Register
                </button>

                <div class="mt-4 text-center text-sm text-slate-600">
                    Already have an account? <a href="/login" class="text-blue-600 hover:underline">Login here</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>