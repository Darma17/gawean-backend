@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Perbarui informasi user')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <a href="{{ route('users.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Edit User</h3>
                    <p class="text-sm text-gray-500">{{ $user->nama }} - {{ $user->email }}</p>
                </div>
            </div>
        </div>
        
        <!-- Form -->
        <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Current Photo -->
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                @if($user->foto_profile)
                    <img src="{{ Storage::url($user->foto_profile) }}" alt="{{ $user->nama }}" class="w-16 h-16 rounded-full object-cover">
                @else
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($user->nama, 0, 1) }}
                    </div>
                @endif
                <div>
                    <p class="font-semibold text-gray-800">{{ $user->nama }}</p>
                    <p class="text-sm text-gray-500">Terdaftar: {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
            
            <!-- Nama -->
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 transition-colors @error('nama') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap">
                @error('nama')
                    <p class="mt-2 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 transition-colors @error('email') border-red-500 @enderror"
                    placeholder="nama@email.com">
                @error('email')
                    <p class="mt-2 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 transition-colors @error('password') border-red-500 @enderror"
                        placeholder="Kosongkan jika tidak diubah">
                    @error('password')
                        <p class="mt-2 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 transition-colors"
                        placeholder="Ulangi password baru">
                </div>
            </div>
            
            <!-- Nomor Telepon -->
            <div>
                <label for="nomor_telepon" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nomor Telepon
                </label>
                <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 transition-colors @error('nomor_telepon') border-red-500 @enderror"
                    placeholder="08xxxxxxxxxx">
                @error('nomor_telepon')
                    <p class="mt-2 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Alamat -->
            <div>
                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                    Alamat
                </label>
                <textarea id="alamat" name="alamat" rows="3"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 transition-colors @error('alamat') border-red-500 @enderror"
                    placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                @error('alamat')
                    <p class="mt-2 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Role & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 transition-colors @error('role') border-red-500 @enderror">
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                        <option value="perusahaan" {{ old('role', $user->role) === 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            class="w-5 h-5 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                        <span class="text-gray-700">User Aktif</span>
                    </label>
                </div>
            </div>
            
            <!-- Foto Profile -->
            <div>
                <label for="foto_profile" class="block text-sm font-semibold text-gray-700 mb-2">
                    Foto Profile
                </label>
                <div class="flex items-center gap-4">
                    <div id="preview" class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if($user->foto_profile)
                            <img id="previewImg" src="{{ Storage::url($user->foto_profile) }}" alt="" class="w-full h-full object-cover">
                            <i class="fas fa-user text-2xl text-gray-400 hidden" id="defaultIcon"></i>
                        @else
                            <i class="fas fa-user text-2xl text-gray-400" id="defaultIcon"></i>
                            <img id="previewImg" src="" alt="" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" id="foto_profile" name="foto_profile" accept="image/*"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 transition-colors @error('foto_profile') border-red-500 @enderror"
                            onchange="previewImage(this)">
                        <p class="mt-2 text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah.</p>
                        @error('foto_profile')
                            <p class="mt-2 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all shadow-lg shadow-primary-500/30">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('users.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('previewImg');
        const defaultIcon = document.getElementById('defaultIcon');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (defaultIcon) defaultIcon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
