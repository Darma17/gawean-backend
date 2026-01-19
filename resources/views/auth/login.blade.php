@extends('layouts.auth')

@section('title', 'Login - Gawean Admin')

@section('content')
<!-- Video Background -->
<video autoplay muted loop playsinline class="video-background">
    <source src="https://cdn.pixabay.com/video/2020/08/09/46912-449623750_large.mp4" type="video/mp4">
</video>
<div class="overlay"></div>

<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md fade-in">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-2xl mb-4 floating">
                <img src="{{ asset('favicon.ico') }}" alt="Logo" class="w-12 h-12">
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Selamat Datang</h1>
            <p class="text-white/80">Masuk ke panel admin Gawean</p>
        </div>
        
        <!-- Login Card -->
        <div class="glass-card rounded-3xl shadow-2xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Login</h2>
                <p class="text-gray-500 mt-1">Masukkan kredensial Anda</p>
            </div>
            
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-primary-50 border border-primary-200 text-primary-700 rounded-xl flex items-center gap-3">
                    <i class="fas fa-check-circle text-primary-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shake">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login.post') }}" class="space-y-5" id="loginForm" onsubmit="handleSubmit(event)">
                @csrf
                
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope text-primary-500 mr-2"></i>Email
                    </label>
                    <div class="relative">
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus
                            class="w-full px-4 py-3 pl-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 input-focus transition-all duration-300"
                            placeholder="nama@email.com"
                        >
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-at"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock text-primary-500 mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 pl-12 pr-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 input-focus transition-all duration-300"
                            placeholder="••••••••"
                        >
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-key"></i>
                        </div>
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-500 transition-colors"
                        >
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="w-full btn-primary text-white font-semibold py-3 px-6 rounded-xl flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                >
                    <span id="btnText" class="flex items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </span>
                    <span id="btnLoading" class="items-center gap-2" style="display: none;">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Memproses...</span>
                    </span>
                </button>
            </form>
            
            <!-- Decorative Elements -->
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-gray-400 text-sm">
                    <i class="fas fa-shield-alt text-primary-500 mr-1"></i>
                    Dilindungi dengan verifikasi OTP
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-white/60 text-sm">
                © {{ date('Y') }} Gawean. All rights reserved.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    
    function handleSubmit(event) {
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');
        
        // Disable button and show loading
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        btnLoading.style.display = 'flex';
        
        // Allow form to submit
        return true;
    }
</script>
@endpush
