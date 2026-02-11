<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - DPMPTSP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bg-gradient-teal {
            background: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #14b8a6 100%);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .btn-hover-effect {
            transition: all 0.3s ease;
        }

        .btn-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(13, 148, 136, 0.2);
        }

        .smooth-transition {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-teal flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-lg shadow-lg overflow-hidden animate-fade-in my-auto">
        <div class="p-6">

            <div class="mb-5 text-center">
                <div class="flex justify-center mb-3">
                    <img src="{{ asset('image/dpm.png') }}"
                         alt="Logo DPMPTSP"
                         class="h-24 w-auto object-contain">
                </div>
                <h1 class="text-2xl font-extrabold text-gray-800 mb-1">Selamat Datang</h1>
                <p class="text-base text-gray-600">Masuk untuk melanjutkan</p>
            </div>

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg smooth-transition">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3 flex-shrink-0"></i>
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg smooth-transition">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-emerald-500 mr-3 flex-shrink-0"></i>
                        <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-4" id="loginForm">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input
                            type="text"
                            name="username"
                            id="username"
                            class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 smooth-transition"
                            placeholder="Masukkan username"
                            required
                            autocomplete="username"
                            autofocus
                        >
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 smooth-transition"
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button"
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-teal-600 smooth-transition">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded smooth-transition"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" class="text-sm text-teal-600 hover:text-teal-700 font-medium smooth-transition">
                        Lupa password?
                    </a>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full btn-hover-effect bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg font-medium smooth-transition"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'pass
