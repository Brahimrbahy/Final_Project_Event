<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Tsna admin</title>
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
            
            <h2 class="text-3xl font-bold text-white mb-3">Application Under Review</h2>
            <p class="text-gray-400 leading-relaxed">Your organizer application is being reviewed by our admin team</p>
        </div>

        <!-- Status Card -->
        <div class="bg-gradient-to-br from-gray-800/30 to-gray-900/30 backdrop-blur-sm rounded-[20px] border border-gray-600/30 p-6 slide-up shadow-xl">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-6 border border-yellow-500/30 animate-bounce-slow">
                    <svg class="w-10 h-10 text-[#48ff91]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Pending Approval</h3>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    Thank you for applying to become an event organizer! Our admin team will review your application and get back to you soon.
                </p>
                
                <!-- Application Details -->
                <div class="bg-gradient-to-r from-gray-700/30 to-gray-800/30 backdrop-blur-sm rounded-[15px] p-5 mb-4 border border-gray-600/30">
                    <h4 class="font-medium text-white mb-4 flex items-center justify-center">
                        <span class="text-xl mr-2">📋</span>
                        Application Details:
                    </h4>
                    <div class="text-sm text-gray-300 space-y-3">
                        <div class="flex justify-between items-center border-b border-gray-600/30 pb-2">
                            <span class="text-gray-400">Name:</span>
                            <span class="text-[#48ff91] font-medium">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-600/30 pb-2">
                            <span class="text-gray-400">Email:</span>
                            <span class="text-[#48ff91] font-medium break-all">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-600/30 pb-2">
                            <span class="text-gray-400">Company:</span>
                            <span class="text-white font-medium">{{ Auth::user()->company_name }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-600/30 pb-2">
                            <span class="text-gray-400">Phone:</span>
                            <span class="text-white font-medium">{{ Auth::user()->contact_phone }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Submitted:</span>
                            <span class="text-white font-medium">{{ Auth::user()->created_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- What Happens Next -->
        <div class="bg-gradient-to-br from-gray-800/30 to-gray-900/30 backdrop-blur-sm rounded-[20px] border border-gray-600/30 p-6 slide-up shadow-xl">
            <h3 class="text-xl font-semibold text-white mb-6 text-center flex items-center justify-center">
                <span class="text-2xl mr-2">🔄</span>
                What happens next?
            </h3>
            <div class="space-y-4">
                <div class="flex items-start process-step" data-step="1">
                    <div class="w-8 h-8 bg-gradient-to-r from-[#48ff91] to-[#052cff] rounded-full flex items-center justify-center mr-4 mt-0.5 shadow-lg">
                        <span class="text-white text-sm font-bold">1</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-white mb-1">Admin Review</p>
                        <p class="text-xs text-gray-400 leading-relaxed">Our team will review your application and company information</p>
                    </div>
                </div>
                <div class="flex items-start process-step" data-step="2">
                    <div class="w-8 h-8 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mr-4 mt-0.5 transition-all duration-500">
                        <span class="text-white text-sm font-bold">2</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-white mb-1">Email Notification</p>
                        <p class="text-xs text-gray-400 leading-relaxed">You'll receive an email once your application is approved</p>
                    </div>
                </div>
                <div class="flex items-start process-step" data-step="3">
                    <div class="w-8 h-8 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mr-4 mt-0.5 transition-all duration-500">
                        <span class="text-white text-sm font-bold">3</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-white mb-1">Start Creating Events</p>
                        <p class="text-xs text-gray-400 leading-relaxed">Access your organizer dashboard and create your first event</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expected Timeline -->
        <div class="bg-gradient-to-r from-[#48ff91]/20 to-[#052cff]/20 backdrop-blur-sm rounded-[20px] p-6 border border-[#48ff91]/30 slide-up shadow-xl shadow-[#48ff91]/10">
            <div class="text-center">
                <h3 class="text-xl font-semibold mb-3 text-white flex items-center justify-center">
                    <span class="text-2xl mr-2 animate-pulse">⏰</span>
                    Expected Timeline
                </h3>
                <p class="text-gray-300 mb-3 text-lg">
                    Most applications are reviewed within <span class="text-[#48ff91] font-bold">24-48 hours</span>
                </p>
                <p class="text-sm text-gray-400 leading-relaxed">
                    We'll email you at <span class="text-[#48ff91] font-semibold break-all">{{ Auth::user()->email }}</span> once approved
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4 slide-up">
            <a href="{{ route('events.index') }}" 
               class="w-full bg-white text-black font-medium py-4 px-4 rounded-[50px] focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 hover:bg-[#48ff91] transition-all duration-200 transform hover:scale-105 text-center block shadow-lg flex items-center justify-center">
                <span class="text-lg mr-2">🎪</span>
                Browse Events While You Wait
            </a>
            
            <div class="flex space-x-3">
                
                
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-gray-700/50 to-gray-800/50 hover:from-red-500/20 hover:to-red-600/20 text-white font-medium py-3 px-4 rounded-[50px] transition-all duration-200 border border-gray-600/30 hover:border-red-500/30 transform hover:scale-105 flex items-center justify-center">
                        <span class="text-lg mr-1">🚪</span>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="text-center slide-up">
            <p class="text-sm text-gray-400 leading-relaxed">
                Questions about your application? 
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
    
    .animate-bounce-slow {
        animation: bounce-slow 2s infinite;
    }
    
    @keyframes bounce-slow {
        0%, 20%, 53%, 80%, 100% {
            animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            animation-timing-function: cubic-bezier(0.755, 0.050, 0.855, 0.060);
            transform: translate3d(0, -5px, 0);
        }
        70% {
            animation-timing-function: cubic-bezier(0.755, 0.050, 0.855, 0.060);
            transform: translate3d(0, -3px, 0);
        }
        90% {
            transform: translate3d(0, -1px, 0);
        }
    }
    
    .animate-clock {
        animation: clock-tick 2s ease-in-out infinite;
    }
    
    @keyframes clock-tick {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(3deg); }
        75% { transform: rotate(-3deg); }
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
    .slide-up:nth-child(7) { animation-delay: 0.7s; animation-fill-mode: both; }
    
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
    
    /* Process step animation */
    .process-step {
        opacity: 0;
        animation: processStepIn 0.6s ease-out forwards;
    }
    
    .process-step[data-step="1"] { animation-delay: 1s; }
    .process-step[data-step="2"] { animation-delay: 1.2s; }
    .process-step[data-step="3"] { animation-delay: 1.4s; }
    
    @keyframes processStepIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>

<script>
    // Animate process steps sequentially
    document.addEventListener('DOMContentLoaded', function() {
        const steps = document.querySelectorAll('.process-step');
        
        // Animate step progress
        setTimeout(() => {
            const step2 = steps[1].querySelector('div');
            const step3 = steps[2].querySelector('div');
            
            setTimeout(() => {
                step2.className = step2.className.replace('from-gray-500 to-gray-600', 'from-[#48ff91] to-[#052cff]');
            }, 2000);
            
            setTimeout(() => {
                step3.className = step3.className.replace('from-gray-500 to-gray-600', 'from-[#48ff91] to-[#052cff]');
            }, 4000);
        }, 1500);
        
        // Add staggered animations to slide elements
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
        const speed = scrolled * 0.3;
        parallax.style.backgroundPosition = `center ${speed}px`;
    });

    // Add floating animation to the clock icon
    const clockIcon = document.querySelector('.animate-clock');
    let floatDirection = 1;
    
    setInterval(() => {
        if (clockIcon) {
            floatDirection *= -1;
            clockIcon.style.transform = `translateY(${floatDirection * 2}px) rotate(${floatDirection * 2}deg)`;
        }
    }, 1000);

    // Add hover effect for application details
    const detailRows = document.querySelectorAll('.flex.justify-between');
    detailRows.forEach(row => {
        row.addEventListener('mouseenter', () => {
            row.style.transform = 'translateX(5px)';
            row.style.transition = 'transform 0.2s ease';
        });
        
        row.addEventListener('mouseleave', () => {
            row.style.transform = 'translateX(0)';
        });
    });
</script>
</body>
</html>