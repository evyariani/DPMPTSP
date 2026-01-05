<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-blue-600 flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-extrabold mb-1">Selamat Datang</h1>
            <p class="text-gray-600">Masuk untuk melanjutkan</p>
        </div>

        @if(session('error'))
            <div class="mb-4 text-red-600 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/login" class="space-y-4" id="loginForm">
            @csrf

            <div>
                <label for="username" class="block text-sm font-medium mb-1">Username</label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-500"
                    placeholder="Masukkan username"
                    required
                    autocomplete="username"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-500"
                    placeholder="Masukkan password"
                    required
                    autocomplete="current-password"
                    onkeypress="if(event.key === 'Enter') document.getElementById('loginForm').submit();"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold"
            >
                Masuk
            </button>
        </form>

    </div>

    <!-- Simple JavaScript untuk Enter key -->
    <script>
        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginForm').submit();
            }
        });
    </script>

</body>
</html>