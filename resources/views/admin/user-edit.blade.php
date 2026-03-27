@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Edit User</h2>
            <p class="text-gray-500">Edit informasi user yang sudah ada</p>
        </div>
    </div>
</div>

<!-- Notifikasi -->
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="font-medium">Terjadi kesalahan:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<!-- Form Edit User -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6">
        <form action="{{ route('user.update', $user->id) }}" method="POST" id="formUser" autocomplete="off">
            @csrf
            @method('PUT')
            
            <!-- Grid 2 kolom untuk informasi user -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="username"
                               name="username" 
                               value="{{ old('username', $user->username) }}"
                               required
                               autocomplete="off"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('username') border-red-500 @enderror"
                               placeholder="Masukkan username"
                               autofocus>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Username harus unik dan minimal 3 karakter</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru 
                            <span class="text-xs text-gray-500">(Kosongkan jika tidak ingin mengubah)</span>
                        </label>
                        <input type="password" 
                               id="password"
                               name="password" 
                               autocomplete="new-password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror"
                               placeholder="Masukkan password baru (minimal 3 karakter)">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Password minimal 3 karakter. Kosongkan jika tidak ingin mengubah password</p>
                    </div>
                    
                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" 
                               id="password_confirmation"
                               name="password_confirmation" 
                               autocomplete="new-password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Konfirmasi password baru">
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    <!-- Level -->
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-2">
                            Level User <span class="text-red-500">*</span>
                        </label>
                        
                        @php
                            $currentUserLevel = $user->level;
                            $adminExists = \App\Models\User::where('level', 'admin')->where('id', '!=', $user->id)->exists();
                            $kadisExists = \App\Models\User::where('level', 'kadis')->where('id', '!=', $user->id)->exists();
                            $selectedLevel = old('level', $user->level);
                        @endphp
                        
                        <select id="level" 
                                name="level" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('level') border-red-500 @enderror">
                            <option value="">Pilih Level User</option>
                            
                            @if($currentUserLevel == 'admin' || !$adminExists)
                                <option value="admin" {{ $selectedLevel == 'admin' ? 'selected' : '' }}>Admin</option>
                            @endif
                            
                            <option value="pegawai" {{ $selectedLevel == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                            
                            @if($currentUserLevel == 'kadis' || !$kadisExists)
                                <option value="kadis" {{ $selectedLevel == 'kadis' ? 'selected' : '' }}>Kepala Dinas (Kadis)</option>
                            @endif
                        </select>
                        
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            @if($currentUserLevel == 'admin')
                                <i class="fas fa-info-circle text-blue-500"></i> Anda sedang mengedit user dengan level Admin
                            @elseif($currentUserLevel == 'kadis')
                                <i class="fas fa-info-circle text-blue-500"></i> Anda sedang mengedit user dengan level Kadis
                            @endif
                        </p>
                    </div>
                    
                    <!-- Informasi Tambahan -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi User</h4>
                        <div class="space-y-2 text-sm">
                            <p class="text-gray-600">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                Dibuat pada: {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}
                            </p>
                            <p class="text-gray-600">
                                <i class="fas fa-edit mr-2 text-gray-400"></i>
                                Terakhir diupdate: {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '-' }}
                            </p>
                            @if($user->last_login)
                                <p class="text-gray-600">
                                    <i class="fas fa-sign-in-alt mr-2 text-gray-400"></i>
                                    Login terakhir: {{ $user->last_login->format('d/m/Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('user.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-save mr-2"></i> Update User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus ke input username
    document.getElementById('username').focus();
});

// Validasi form sebelum submit
document.getElementById('formUser').addEventListener('submit', function(e) {
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    // Validasi Username
    if (!username.value.trim()) {
        alert('Username wajib diisi!');
        e.preventDefault();
        username.focus();
        return false;
    }
    
    if (username.value.trim().length < 3) {
        alert('Username minimal 3 karakter!');
        e.preventDefault();
        username.focus();
        return false;
    }
    
    // Validasi Password (jika diisi)
    if (password.value) {
        if (password.value.length < 3) {
            alert('Password minimal 3 karakter!');
            e.preventDefault();
            password.focus();
            return false;
        }
        
        if (password.value !== passwordConfirmation.value) {
            alert('Password dan konfirmasi password tidak cocok!');
            e.preventDefault();
            passwordConfirmation.focus();
            return false;
        }
    }
    
    return true;
});

// Optional: Tambahkan konfirmasi sebelum menyimpan perubahan
document.querySelector('form').addEventListener('submit', function(e) {
    const hasChanges = checkForChanges();
    if (hasChanges) {
        if (!confirm('Apakah Anda yakin ingin menyimpan perubahan?')) {
            e.preventDefault();
        }
    }
});

function checkForChanges() {
    const username = document.getElementById('username').value;
    const originalUsername = "{{ addslashes($user->username) }}";
    const level = document.getElementById('level').value;
    const originalLevel = "{{ $user->level }}";
    const password = document.getElementById('password').value;
    
    return (username !== originalUsername || level !== originalLevel || password !== '');
}
</script>
@endsection