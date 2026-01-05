@extends('admin')

@section('title', 'Tambah User Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-50 border-b px-6 py-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-user-plus text-blue-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah User Baru</h3>
                    <p class="text-sm text-gray-600">Isi form di bawah untuk menambahkan user baru</p>
                </div>
            </div>
        </div>
        
        <!-- Form -->
        <div class="p-6">
            <form action="/user" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="username" 
                               required 
                               value="{{ old('username') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('username') border-red-500 @enderror"
                               placeholder="Masukkan username">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="Masukkan password (minimal 6 karakter)">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ulangi password">
                    </div>
                    
                    <!-- Level -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Level <span class="text-red-500">*</span>
                        </label>
                        <select name="level" 
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('level') border-red-500 @enderror">
                            <option value="">Pilih Level User</option>
                            <option value="admin" {{ old('level') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pegawai" {{ old('level') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                            <option value="pemimpin" {{ old('level') == 'pemimpin' ? 'selected' : '' }}>Pemimpin</option>
                            <option value="admin_keuangan" {{ old('level') == 'admin_keuangan' ? 'selected' : '' }}>Admin Keuangan</option>
                        </select>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <div class="mt-3 grid grid-cols-2 gap-3">
                            <div class="bg-purple-50 border border-purple-200 rounded p-3">
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                                    <span class="text-sm font-medium text-purple-800">Admin</span>
                                </div>
                                <p class="text-xs text-purple-600 mt-1">Akses penuh ke semua fitur</p>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded p-3">
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                                    <span class="text-sm font-medium text-blue-800">Pegawai</span>
                                </div>
                                <p class="text-xs text-blue-600 mt-1">Akses terbatas</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <a href="/user" 
                               class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-150">
                                <i class="fas fa-times mr-2"></i> Batal
                            </a>
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-150">
                                <i class="fas fa-save mr-2"></i> Simpan User
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection