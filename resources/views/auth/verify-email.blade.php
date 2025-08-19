<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Email Oppo </title>
</head>
<body>
    
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background-color: #0B1623;">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo with subtle animation -->
            <div class="text-center mb-8 slide-up">
                <a href="{{ route('welcome') }}" class="inline-block group">
                    <h1 class="text-4xl font-bold text-white tracking-wide mb-4 transition-transform group-hover:scale-105">
                        <span class="text-[#48ff91]">My</span>
                        <span class="text-white">Guichet</span>
                    </h1>
                </a>
            </div>

            <!-- Header -->
            <div class="text-center slide-up">
                
                <h2 class="text-3xl font-bold text-white mb-3">Check Your Email</h2>
                <p class="text-gray-400 leading-relaxed">We've sent a verification link to your email address</p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-gradient-to-r from-green-500/20 to-green-600/20 border border-green-500/30 rounded-[20px] p-4 slide-up backdrop-blur-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-[#48ff91]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status Message -->
            @if (session('status') == 'verification-link-sent')
                <div class="bg-gradient-to-r from-green-500/20 to-green-600/20 border border-green-500/30 rounded-[20px] p-4 slide-up backdrop-blur-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-[#48ff91] animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">
                                A new verification link has been sent to your email address.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div class="bg-gradient-to-br from-gray-800/30 to-gray-900/30 backdrop-blur-sm rounded-[20px] border border-gray-600/30 p-6 slide-up">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500/20 to-purple-500/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-500/30">
                        <svg class="w-8 h-8 text-[#48ff91]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-white mb-2">Verify Your Email Address</h3>
                    <p class="text-gray-400 mb-4 leading-relaxed">
                        Before you can start booking events, please verify your email address by clicking the link we sent to:
                    </p>
                    
                    <div class="bg-gradient-to-r from-gray-700/30 to-gray-800/30 backdrop-blur-sm rounded-[15px] p-4 mb-4 border border-gray-600/30">
                        <p class="font-medium text-[#48ff91] break-all">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <p class="text-sm text-gray-400 mb-6 leading-relaxed">
                        The verification link will expire in <span class="text-[#48ff91] font-medium">60 minutes</span> for security reasons.
                    </p>
                </div>

                <!-- Instructions -->
                <div class="bg-gradient-to-r from-blue-500/10 to-purple-500/10 backdrop-blur-sm rounded-[15px] p-4 mb-6 border border-blue-500/20">
                    <h4 class="font-medium text-white mb-3 flex items-center">
                        <span class="text-2xl mr-2">📧</span>
                        What to do next:
                    </h4>
                    <ol class="text-sm text-gray-300 space-y-2 list-decimal list-inside">
                        <li class="flex items-start">
                            <span class="text-[#48ff91] mr-2">1.</span>
                            <span>Check your email inbox (and spam folder)</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-[#48ff91] mr-2">2.</span>
                            <span>Look for an email from MyGuichet</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-[#48ff91] mr-2">3.</span>
                            <span>Click the "Verify Email Address" button</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-[#48ff91] mr-2">4.</span>
                            <span>You'll be redirected back to your dashboard</span>
                        </li>
                    </ol>
                </div>

                <!-- Resend Email Form -->
                <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                    @csrf
                    <button type="submit" id="resendBtn"
                            class="w-full bg-white text-black font-medium py-4 px-4 rounded-[50px] focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 hover:bg-[#48ff91] transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" id="resendIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span id="resendText">Resend Verification Email</span>
                    </button>
                </form>

                <!-- Help Text -->
                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-2 leading-relaxed">
                        Didn't receive the email? Check your spam folder or click the button above to resend.
                    </p>
                </div>
            </div>

            <!-- Alternative Actions -->
            <div class="bg-gradient-to-br from-gray-800/30 to-gray-900/30 backdrop-blur-sm rounded-[20px] border border-gray-600/30 p-6 slide-up">
                <h3 class="text-lg font-semibold text-white mb-6 text-center">Need Help?</h3>
                
                <div class="space-y-4">
                    <!-- Change Email -->
                    <a href="{{ route('profile.edit') }}" 
                       class="w-full bg-gradient-to-r from-gray-700/50 to-gray-800/50 hover:from-[#48ff91]/20 hover:to-[#052cff]/20 text-white font-medium py-3 px-4 rounded-[50px] transition-all duration-200 text-center block border border-gray-600/30 hover:border-[#48ff91]/30 transform hover:scale-105 flex items-center justify-center">
                        <span class="text-lg mr-2">📝</span>
                        Update Email Address
                    </a>
                    
                    <!-- Browse Events -->
                    <a href="{{ route('events.index') }}" 
                       class="w-full bg-gradient-to-r from-gray-700/50 to-gray-800/50 hover:from-[#48ff91]/20 hover:to-[#052cff]/20 text-white font-medium py-3 px-4 rounded-[50px] transition-all duration-200 text-center block border border-gray-600/30 hover:border-[#48ff91]/30 transform hover:scale-105 flex items-center justify-center">
                        <span class="text-lg mr-2">👀</span>
                        Browse Events (View Only)
                    </a>
                    
                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-gray-700/50 to-gray-800/50 hover:from-red-500/20 hover:to-red-600/20 text-white font-medium py-3 px-4 rounded-[50px] transition-all duration-200 border border-gray-600/30 hover:border-red-500/30 transform hover:scale-105 flex items-center justify-center">
                            <span class="text-lg mr-2">🚪</span>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="text-center slide-up">
                <p class="text-sm text-gray-400 leading-relaxed">
                    Still having trouble? 
                    <a href="mailto:support@myguichet.com" class="text-[#48ff91] hover:text-[#052cff] font-medium transition-colors duration-200 underline">
                        Contact Support
                    </a>
                </p>
                <p class="text-xs text-gray-500 mt-3">
                    <a href="{{ route('welcome') }}" class="hover:text-gray-300 transition-colors duration-200">
                        ← Back to Home
                    </a>
                </p>
            </div>
        </div>
    </div>

    <style>
        .slide-up {
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse-slow {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.8;
                transform: scale(1.05);
            }
        }
        
        .backdrop-blur-sm {
            backdrop-filter: blur(8px);
        }
        
        /* Staggered animation delays */
        .slide-up:nth-child(1) { animation-delay: 0.1s; animation-fill-mode: both; }
        .slide-up:nth-child(2) { animation-delay: 0.2s; animation-fill-mode: both; }
        .slide-up:nth-child(3) { animation-delay: 0.3s; animation-fill-mode: both; }
        .slide-up:nth-child(4) { animation-delay: 0.4s; animation-fill-mode: both; }
        .slide-up:nth-child(5) { animation-delay: 0.5s; animation-fill-mode: both; }
        .slide-up:nth-child(6) { animation-delay: 0.6s; animation-fill-mode: both; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(72, 255, 145, 0.3);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(72, 255, 145, 0.5);
        }
    </style>

    <script>
        // Add loading state to resend button
        document.getElementById('resendBtn').addEventListener('click', function() {
            const btn = this;
            const text = document.getElementById('resendText');
            const icon = document.getElementById('resendIcon');
            
            btn.disabled = true;
            text.textContent = 'Sending...';
            
            // Add spinning animation to icon
            icon.style.animation = 'spin 1s linear infinite';
            
            // Re-enable after 3 seconds (in case of no redirect)
            setTimeout(() => {
                btn.disabled = false;
                text.textContent = 'Resend Verification Email';
                icon.style.animation = '';
            }, 3000);
        });

        // Add CSS for spin animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // Add staggered animations to child elements
        document.addEventListener('DOMContentLoaded', function() {
            const slideElements = document.querySelectorAll('.slide-up');
            slideElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.1}s`;
                element.style.animationFillMode = 'both';
            });
        });

        // Add subtle parallax effect on scroll
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.min-h-screen');
            const speed = scrolled * 0.5;
            parallax.style.backgroundPosition = `center ${speed}px`;
        });
    </script>

</body>
</html>