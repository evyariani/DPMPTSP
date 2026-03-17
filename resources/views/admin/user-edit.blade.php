@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Edit User</h2>
            <p class="text-gray-500">Edit informasi user {{ $user->username }}</p>
        </div>
        <a href="{{ route('user.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<!-- Notifikasi -->
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

                    <!-- Password (Opsional) -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-gray-500 text-xs">(Kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" 
                               id="password"
                               name="password" 
                               autocomplete="new-password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Masukkan password baru (minimal 3 karakter)">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
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
                            $adminExists = \App\Models\User::where('level', 'admin')
                                ->where('id', '!=', $user->id)
                                ->exists();
                            $kadisExists = \App\Models\User::where('level', 'kadis')
                                ->where('id', '!=', $user->id)
                                ->exists();
                        @endphp
                        
                        @if($user->level == 'pegawai' && $adminExists && $kadisExists)
                            {{-- Jika user adalah pegawai, dan admin & kadis sudah ada --}}
                            <input type="hidden" name="level" value="pegawai">
                            <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                                Pegawai (Otomatis - Admin & Kadis sudah ada)
                            </div>
                        @else
                            {{-- Tampilkan dropdown --}}
                            <select id="level" 
                                    name="level" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('level') border-red-500 @enderror">
                                <option value="">Pilih Level User</option>
                                
                                @if(!$adminExists || $user->level == 'admin')
                                    <option value="admin" {{ old('level', $user->level) == 'admin' ? 'selected' : '' }}>Admin</option>
                                @endif
                                
                                <option value="pegawai" {{ old('level', $user->level) == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                
                                @if(!$kadisExists || $user->level == 'kadis')
                                    <option value="kadis" {{ old('level', $user->level) == 'kadis' ? 'selected' : '' }}>Kepala Dinas (Kadis)</option>
                                @endif
                            </select>
                        @endif
                        
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
// Clear password field on page load untuk mencegah cache
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus ke input username
    document.getElementById('username').focus();
    
    // Clear password field
    document.getElementById('password').value = '';
});

// Validasi form sebelum submit
document.getElementById('formUser').addEventListener('submit', function(e) {
    const username = document.getElementById('username');
    
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
    
    return true;
});
</script>
@endsection