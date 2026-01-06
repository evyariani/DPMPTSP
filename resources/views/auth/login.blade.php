<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - DPMPTSP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom gradient untuk background */
        .bg-gradient-teal {
            background: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #14b8a6 100%);
        }
        
        /* Animasi untuk logo */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        /* Hover effect untuk button */
        .btn-hover-effect {
            transition: all 0.3s ease;
        }
        
        .btn-hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(13, 148, 136, 0.2);
        }
        
        /* Smooth transition untuk input */
        .smooth-transition {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-teal flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-lg shadow-lg overflow-hidden animate-fade-in">
        <!-- Logo, Judul, dan Form dalam satu card - MARGIN SEPERTI REFERENSI -->
        <div class="p-6">
            <!-- Logo dan Judul - MARGIN SEPERTI REFERENSI -->
            <div class="mb-5 text-center">
                <!-- Logo DPMPTSP - ukuran seperti referensi -->
                <div class="flex justify-center mb-2">
                    <img src="{{ asset('image/dpm.png') }}" 
                         alt="Logo DPMPTSP" 
                         class="h-36 w-auto object-contain">
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

                <!-- Username Field -->
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

                <!-- Password Field -->
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

                <!-- Remember Me dan Lupa Password -->
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

                <!-- Submit Button -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full btn-hover-effect bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg font-medium smooth-transition"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} DPMPTSP Management System
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Hak cipta dilindungi undang-undang
                </p>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Enter key submit
        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginForm').submit();
            }
        });

        // Auto focus pada username
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!username || !password) {
                e.preventDefault();
                
                // Animasi shake untuk input kosong
                const shake = 'animate-shake';
                
                if (!username) {
                    const usernameInput = document.getElementById('username');
                    usernameInput.classList.add('border-red-500', shake);
                    setTimeout(() => {
                        usernameInput.classList.remove(shake);
                    }, 500);
                }
                
                if (!password) {
                    const passwordInput = document.getElementById('password');
                    passwordInput.classList.add('border-red-500', shake);
                    setTimeout(() => {
                        passwordInput.classList.remove(shake);
                    }, 500);
                }
            }
        });

        // Remove red border when user starts typing
        document.getElementById('username').addEventListener('input', function() {
            this.classList.remove('border-red-500');
        });
        
        document.getElementById('password').addEventListener('input', function() {
            this.classList.remove('border-red-500');
        });

        // Add loading animation on submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            const originalHTML = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            button.disabled = true;
            button.classList.add('opacity-75', 'cursor-not-allowed');
            
            // Reset button after 5 seconds (safety timeout)
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.disabled = false;
                button.classList.remove('opacity-75', 'cursor-not-allowed');
            }, 5000);
        });

        // Add shake animation style
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            .animate-shake {
                animation: shake 0.5s ease-in-out;
            }
        `;
        document.head.appendChild(style);
    </script>

</body>
</html>