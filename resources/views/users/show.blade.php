@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', 'Detail User')
@section('page-subtitle', 'Informasi lengkap user')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('users.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Detail User</h3>
                        <p class="text-sm text-gray-500">ID: #{{ $user->id }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('users.edit', $user) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Profile Card -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mb-8 pb-8 border-b border-gray-100">
                @if($user->foto_profile)
                    <img src="{{ Storage::url($user->foto_profile) }}" alt="{{ $user->nama }}" class="w-32 h-32 rounded-2xl object-cover shadow-lg">
                @else
                    <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-5xl font-bold shadow-lg">
                        {{ substr($user->nama, 0, 1) }}
                    </div>
                @endif
                <div class="text-center md:text-left">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $user->nama }}</h2>
                    <p class="text-gray-500 mt-1">{{ $user->email }}</p>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-4">
                        <span class="px-3 py-1 text-sm font-medium rounded-full 
                            @if($user->role === 'admin') bg-red-100 text-red-700
                            @elseif($user->role === 'perusahaan') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700 @endif">
                            <i class="fas fa-user-tag mr-1"></i>{{ ucfirst($user->role) }}
                        </span>
                        @if($user->is_active)
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-700">
                                <i class="fas fa-check-circle mr-1"></i>Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-700">
                                <i class="fas fa-times-circle mr-1"></i>Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nomor Telepon</p>
                    <p class="text-gray-800 font-medium">
                        @if($user->nomor_telepon)
                            <i class="fas fa-phone text-primary-500 mr-2"></i>{{ $user->nomor_telepon }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Email Verified</p>
                    <p class="text-gray-800 font-medium">
                        @if($user->email_verified_at)
                            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>{{ $user->email_verified_at->format('d M Y H:i') }}</span>
                        @else
                            <span class="text-gray-400">Belum diverifikasi</span>
                        @endif
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4 md:col-span-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Alamat</p>
                    <p class="text-gray-800 font-medium">
                        @if($user->alamat)
                            <i class="fas fa-map-marker-alt text-primary-500 mr-2"></i>{{ $user->alamat }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Tanggal Daftar</p>
                    <p class="text-gray-800 font-medium">
                        <i class="fas fa-calendar text-primary-500 mr-2"></i>{{ $user->created_at->format('d M Y H:i') }}
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Terakhir Update</p>
                    <p class="text-gray-800 font-medium">
                        <i class="fas fa-clock text-primary-500 mr-2"></i>{{ $user->updated_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('users.edit', $user) }}" class="px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all shadow-lg shadow-primary-500/30">
                    <i class="fas fa-edit mr-2"></i>Edit User
                </a>
                <a href="{{ route('users.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
