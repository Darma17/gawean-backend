@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data dan statistik aplikasi')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="card-hover bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Users</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalUsers ?? 0 }}</p>
                <p class="text-sm text-primary-600 mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>{{ $newUsersThisMonth ?? 0 }} bulan ini</span>
                </p>
            </div>
            <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-users text-2xl text-primary-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Companies -->
    <div class="card-hover bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Perusahaan</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalCompanies ?? 0 }}</p>
                <p class="text-sm text-blue-600 mt-2">
                    <i class="fas fa-building mr-1"></i>
                    <span>Terdaftar</span>
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-building text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Jobs -->
    <div class="card-hover bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Lowongan</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalJobs ?? 0 }}</p>
                <p class="text-sm text-amber-600 mt-2">
                    <i class="fas fa-briefcase mr-1"></i>
                    <span>{{ $activeJobs ?? 0 }} aktif</span>
                </p>
            </div>
            <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-briefcase text-2xl text-amber-600"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Applications -->
    <div class="card-hover bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Lamaran</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalApplications ?? 0 }}</p>
                <p class="text-sm text-purple-600 mt-2">
                    <i class="fas fa-file-alt mr-1"></i>
                    <span>{{ $pendingApplications ?? 0 }} pending</span>
                </p>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-file-alt text-2xl text-purple-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Users -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">User Terbaru</h3>
                <a href="{{ route('users.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            @if(isset($recentUsers) && $recentUsers->count() > 0)
                <div class="space-y-4">
                    @foreach($recentUsers as $user)
                        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold">
                                {{ substr($user->nama, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 truncate">{{ $user->nama }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                            <div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    @if($user->role === 'admin') bg-red-100 text-red-700
                                    @elseif($user->role === 'perusahaan') bg-blue-100 text-blue-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500">Belum ada user</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Aksi Cepat</h3>
        </div>
        <div class="p-6 space-y-3">
            <a href="{{ route('users.create') }}" class="flex items-center gap-3 p-4 rounded-xl border-2 border-dashed border-gray-200 hover:border-primary-500 hover:bg-primary-50 transition-all group">
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500 transition-colors">
                    <i class="fas fa-user-plus text-primary-600 group-hover:text-white"></i>
                </div>
                <span class="font-medium text-gray-700">Tambah User</span>
            </a>
            
            <a href="{{ route('jobs.create') }}" class="flex items-center gap-3 p-4 rounded-xl border-2 border-dashed border-gray-200 hover:border-primary-500 hover:bg-primary-50 transition-all group">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500 transition-colors">
                    <i class="fas fa-plus-circle text-amber-600 group-hover:text-white"></i>
                </div>
                <span class="font-medium text-gray-700">Tambah Lowongan</span>
            </a>
            
            <a href="{{ route('company-profiles.create') }}" class="flex items-center gap-3 p-4 rounded-xl border-2 border-dashed border-gray-200 hover:border-primary-500 hover:bg-primary-50 transition-all group">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-primary-500 transition-colors">
                    <i class="fas fa-building text-blue-600 group-hover:text-white"></i>
                </div>
                <span class="font-medium text-gray-700">Tambah Perusahaan</span>
            </a>
        </div>
        
        <!-- System Info -->
        <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
            <h4 class="text-sm font-semibold text-gray-500 mb-3">INFORMASI SISTEM</h4>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Laravel Version</span>
                    <span class="font-medium text-gray-700">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">PHP Version</span>
                    <span class="font-medium text-gray-700">{{ phpversion() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Environment</span>
                    <span class="font-medium text-gray-700">{{ app()->environment() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Jobs -->
<div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Lowongan Terbaru</h3>
            <a href="{{ route('jobs.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        @if(isset($recentJobs) && $recentJobs->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left bg-gray-50">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Posisi</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Perusahaan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentJobs as $job)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-800">{{ $job->title ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $job->user->nama ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $job->lokasi_kerja ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                    {{ ucfirst($job->tipe ?? 'onsite') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm">{{ $job->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-briefcase text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500">Belum ada lowongan</p>
            </div>
        @endif
    </div>
</div>
@endsection
