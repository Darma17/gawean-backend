@extends('layouts.auth')

@section('title', 'Verifikasi OTP - Gawean Admin')

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
            <h1 class="text-3xl font-bold text-white mb-2">Verifikasi OTP</h1>
            <p class="text-white/80">Masukkan kode yang dikirim ke email Anda</p>
        </div>
        
        <!-- OTP Card -->
        <div class="glass-card rounded-3xl shadow-2xl p-8">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                    <i class="fas fa-envelope-open-text text-3xl text-primary-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Cek Email Anda</h2>
                <p class="text-gray-500 mt-2">Kami telah mengirim kode 6 digit ke</p>
                <p class="text-primary-600 font-semibold">{{ session('otp_email') ? Str::mask(session('otp_email'), '*', 3, strlen(explode('@', session('otp_email'))[0]) - 3) : '' }}</p>
            </div>
            
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-primary-50 border border-primary-200 text-primary-700 rounded-xl flex items-center gap-3">
                    <i class="fas fa-check-circle text-primary-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shake" id="errorAlert">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('otp.verify') }}" class="space-y-6" id="otpForm">
                @csrf
                
                <!-- OTP Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 text-center">
                        Masukkan Kode OTP
                    </label>
                    <div class="flex justify-center gap-2" id="otpInputs">
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 input-focus transition-all duration-300" data-index="0">
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 input-focus transition-all duration-300" data-index="1">
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 input-focus transition-all duration-300" data-index="2">
                        <span class="flex items-center text-gray-300 text-2xl">-</span>
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 input-focus transition-all duration-300" data-index="3">
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 input-focus transition-all duration-300" data-index="4">
                        <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 input-focus transition-all duration-300" data-index="5">
                    </div>
                    <input type="hidden" name="otp" id="otpHidden">
                </div>
                
                <!-- Timer -->
                <div class="text-center">
                    <p class="text-gray-500 text-sm">
                        <i class="fas fa-clock text-primary-500 mr-1"></i>
                        Kode kadaluarsa dalam <span id="timer" class="font-bold text-primary-600">05:00</span>
                    </p>
                </div>
                
                <!-- Submit Button -->
                <button 
                    type="submit" 
                    id="verifyBtn"
                    disabled
                    class="w-full btn-primary text-white font-semibold py-3 px-6 rounded-xl flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
                >
                    <i class="fas fa-check-circle"></i>
                    <span>Verifikasi</span>
                </button>
            </form>
            
            <!-- Resend OTP -->
            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm mb-2">Tidak menerima kode?</p>
                <button 
                    type="button" 
                    id="resendBtn"
                    onclick="resendOtp()"
                    disabled
                    class="text-primary-600 font-semibold hover:text-primary-700 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors"
                >
                    <i class="fas fa-redo mr-1"></i>
                    Kirim Ulang (<span id="resendTimer">60</span>s)
                </button>
            </div>
            
            <!-- Back to Login -->
            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 text-sm transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Login
                </a>
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

@push('styles')
<style>
    .otp-input:focus {
        border-color: #10b981;
        background-color: #ecfdf5;
    }
    
    .otp-input.filled {
        border-color: #10b981;
        background-color: #ecfdf5;
    }
    
    .otp-input.error {
        border-color: #ef4444;
        background-color: #fef2f2;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpHidden = document.getElementById('otpHidden');
        const verifyBtn = document.getElementById('verifyBtn');
        const otpForm = document.getElementById('otpForm');
        
        // OTP Input handling
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                const value = e.target.value;
                
                // Only allow numbers
                if (!/^\d*$/.test(value)) {
                    e.target.value = '';
                    return;
                }
                
                if (value.length === 1) {
                    input.classList.add('filled');
                    // Move to next input
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                } else {
                    input.classList.remove('filled');
                }
                
                updateOtpValue();
            });
            
            input.addEventListener('keydown', function(e) {
                // Handle backspace
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                    otpInputs[index - 1].value = '';
                    otpInputs[index - 1].classList.remove('filled');
                }
                
                // Handle paste
                if (e.key === 'v' && (e.ctrlKey || e.metaKey)) {
                    e.preventDefault();
                    navigator.clipboard.readText().then(text => {
                        const digits = text.replace(/\D/g, '').slice(0, 6);
                        digits.split('').forEach((digit, i) => {
                            if (otpInputs[i]) {
                                otpInputs[i].value = digit;
                                otpInputs[i].classList.add('filled');
                            }
                        });
                        updateOtpValue();
                        if (digits.length === 6) {
                            otpInputs[5].focus();
                        }
                    });
                }
            });
            
            input.addEventListener('focus', function() {
                input.select();
            });
        });
        
        function updateOtpValue() {
            let otp = '';
            otpInputs.forEach(input => {
                otp += input.value;
            });
            otpHidden.value = otp;
            
            // Enable/disable verify button
            verifyBtn.disabled = otp.length !== 6;
        }
        
        // Timer for OTP expiry
        let timeLeft = 300; // 5 minutes
        const timerDisplay = document.getElementById('timer');
        
        const countdown = setInterval(function() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerDisplay.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerDisplay.textContent = 'Kadaluarsa';
                timerDisplay.classList.add('text-red-500');
                verifyBtn.disabled = true;
            }
            
            timeLeft--;
        }, 1000);
        
        // Resend timer
        let resendTime = 60;
        const resendBtn = document.getElementById('resendBtn');
        const resendTimerDisplay = document.getElementById('resendTimer');
        
        const resendCountdown = setInterval(function() {
            resendTime--;
            resendTimerDisplay.textContent = resendTime;
            
            if (resendTime <= 0) {
                clearInterval(resendCountdown);
                resendBtn.disabled = false;
                resendBtn.innerHTML = '<i class="fas fa-redo mr-1"></i> Kirim Ulang';
            }
        }, 1000);
        
        // Focus first input on load
        otpInputs[0].focus();
    });
    
    function resendOtp() {
        const resendBtn = document.getElementById('resendBtn');
        resendBtn.disabled = true;
        resendBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
        
        fetch('{{ route("otp.resend") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert(data.message);
                
                // Reset resend timer
                let resendTime = 60;
                const resendTimerDisplay = document.getElementById('resendTimer');
                resendBtn.innerHTML = '<i class="fas fa-redo mr-1"></i> Kirim Ulang (<span id="resendTimer">60</span>s)';
                
                const newResendCountdown = setInterval(function() {
                    resendTime--;
                    document.getElementById('resendTimer').textContent = resendTime;
                    
                    if (resendTime <= 0) {
                        clearInterval(newResendCountdown);
                        resendBtn.disabled = false;
                        resendBtn.innerHTML = '<i class="fas fa-redo mr-1"></i> Kirim Ulang';
                    }
                }, 1000);
                
                // Reset main timer
                location.reload();
            } else {
                alert(data.message);
                resendBtn.disabled = false;
                resendBtn.innerHTML = '<i class="fas fa-redo mr-1"></i> Kirim Ulang';
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan. Silakan coba lagi.');
            resendBtn.disabled = false;
            resendBtn.innerHTML = '<i class="fas fa-redo mr-1"></i> Kirim Ulang';
        });
    }
</script>
@endpush
