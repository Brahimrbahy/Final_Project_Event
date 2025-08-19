<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .form-field {
            position: relative;
        }
        
        .form-field input:focus + .floating-label,
        .form-field input:not(:placeholder-shown) + .floating-label,
        .form-field textarea:focus + .floating-label,
        .form-field textarea:not(:placeholder-shown) + .floating-label {
            transform: translateY(-28px) scale(0.85);
            color: #3b82f6;
        }
        
        .floating-label {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: #0B1623;
            padding: 0 4px;
            color: #9ca3af;
            pointer-events: none;
            transition: all 0.2s ease-in-out;
            font-size: 14px;
            font-weight: 500;
        }

        .floating-label.textarea-label {
            top: 16px;
            transform: translateY(0);
        }

        .form-field textarea:focus + .floating-label.textarea-label,
        .form-field textarea:not(:placeholder-shown) + .floating-label.textarea-label {
            transform: translateY(-28px) scale(0.85);
            color: #3b82f6;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
            transition: color 0.2s;
        }
        
        .password-toggle:hover {
            color: #ffffff;
        }
        
        .progress-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 8px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #22c55e);
            border-radius: 2px;
            transition: width 0.3s ease;
            width: 0%;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            z-index: 1;
        }
        
        .input-with-icon {
            padding-left: 40px;
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 20%, 40%, 60%, 80% { transform: translateX(-2px); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(2px); }
            100% { transform: translateX(0); }
        }
        
        .slide-up {
            animation: slideUp 0.3s ease-out;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-step {
            opacity: 0.6;
            transition: opacity 0.3s ease;
        }
        
        .form-step.active {
            opacity: 1;
        }
        
        .tooltip {
            position: relative;
            display: inline-block;
        }
        
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: #1f2937;
            color: #ffffff;
            text-align: left;
            border-radius: 8px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            border: 1px solid #374151;
        }
        
        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
        
        .success-check {
            color: #22c55e;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .success-check.show {
            opacity: 1;
        }

        .section-header {
            border-bottom: 2px solid #374151;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .section-header h3 {
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center" style="background-color: #0B1623;">
        <div class="w-full max-w-2xl px-8 py-8">
            <!-- Logo with subtle animation -->
            <div class="text-center mb-8 slide-up">
                <a href="{{ route('welcome') }}" class="inline-block group">
                    <h1 class="text-4xl font-bold text-white tracking-wide mb-4 transition-transform group-hover:scale-105">
                        <span class="text-[#48ff91]">My</span>
                        <span class="text-white">Guichet</span>
                    </h1>
                </a>
            </div>

            <!-- Title with better spacing -->
            <div class="mb-8 text-center slide-up">
                <h2 class="text-3xl font-normal text-white mb-3">Become an Event Organizer</h2>
                <p class="text-gray-400 leading-relaxed">Join our platform and start creating amazing events today</p>
            </div>

            <!-- Form Progress Indicator -->
            <div class="mb-6 slide-up">
                <div class="flex justify-between text-xs text-gray-400 mb-2">
                    <span>Application Details</span>
                    <span>Ready to Submit</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
            </div>

            <form method="POST" action="{{ route('register.organizer') }}" class="space-y-8" id="registrationForm">
                @csrf
                <input type="hidden" name="role" value="organizer">

                <!-- Personal Information Section -->
                <div class="form-step slide-up">
                    <div class="section-header">
                        <h3>Personal Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="form-field input-group">
                            <div class="input-icon">
                               
                            </div>
                            <input id="name" name="name" type="text" autocomplete="name" required placeholder=" "
                                   class="w-full px-4 py-3 input-with-icon bg-transparent border rounded-[50px] border-gray-600 text-white placeholder-transparent focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200"
                                   value="{{ old('name') }}">
                            <label for="name" class="floating-label">Full Name *</label>
                            <div class="success-check absolute right-12 top-1/2 transform -translate-y-1/2">
                                
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-field input-group">
                            <div class="input-icon">
                               
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required placeholder=" "
                                   class="w-full px-4 py-3 input-with-icon bg-transparent border rounded-[50px] border-gray-600 text-white placeholder-transparent focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200"
                                   value="{{ old('email') }}">
                            <label for="email" class="floating-label">Email Address *</label>
                            <div class="success-check absolute right-12 top-1/2 transform -translate-y-1/2">
                               
                            </div>
                        </div>
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-400 slide-up">{{ $message }}</p>
                    @enderror
                    @error('email')
                        <p class="mt-2 text-sm text-red-400 slide-up">{{ $message }}</p>
                    @enderror

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- Password -->
                        <div class="form-field input-group">
                            <div class="input-icon">
                               
                            </div>
                            <input id="password" name="password" type="password" autocomplete="new-password" required placeholder=" "
                                   class="w-full px-4 py-3 input-with-icon pr-12 bg-transparent border rounded-[50px] border-gray-600 text-white placeholder-transparent focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200">
                            <label for="password" class="floating-label">Password *</label>
                            <div class="password-toggle" onclick="togglePassword('password')">
                                
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-field input-group">
                            <div class="input-icon">
                               
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required placeholder=" "
                                   class="w-full px-4 py-3 input-with-icon pr-12 bg-transparent border rounded-[50px] border-gray-600 text-white placeholder-transparent focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200">
                            <label for="password_confirmation" class="floating-label">Confirm Password *</label>
                            <div class="password-toggle" onclick="togglePassword('password_confirmation')">
                                
                            </div>
                            <div class="success-check absolute right-12 top-1/2 transform -translate-y-1/2" id="password-match-check">
                               
                            </div>
                        </div>
                    </div>
                    
                    <div class="tooltip mt-1 inline-block">
                        <div class="text-xs text-gray-400 cursor-help">Password Strength</div>
                        <div class="tooltiptext">
                            <div id="password-requirements">
                                <div class="requirement" data-requirement="length">• At least 8 characters</div>
                                <div class="requirement" data-requirement="uppercase">• One uppercase letter</div>
                                <div class="requirement" data-requirement="lowercase">• One lowercase letter</div>
                                <div class="requirement" data-requirement="number">• One number</div>
                            </div>
                        </div>
                    </div>
                    <div class="progress-bar mt-2">
                        <div class="progress-fill" id="passwordStrength"></div>
                    </div>
                    <div class="text-xs text-gray-400 mt-2" id="password-match-message"></div>
                    
                    @error('password')
                        <p class="mt-2 text-sm text-red-400 slide-up">{{ $message }}</p>
                    @enderror
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-400 slide-up">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company Information Section -->
                <div class="form-step slide-up">
                    <div class="section-header">
                        <h3>Company Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Company Name -->
                        <div class="form-field input-group">
                            <div class="input-icon">
                               
                            </div>
                            <input id="company_name" name="company_name" type="text" required placeholder=" "
                                   class="w-full px-4 py-3 input-with-icon bg-transparent border rounded-[50px] border-gray-600 text-white placeholder-transparent focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200"
                                   value="{{ old('company_name') }}">
                            <label for="company_name" class="floating-label">Company Name *</label>
                            <div class="success-check absolute right-12 top-1/2 transform -translate-y-1/2">
                                
                            </div>
                        </div>

                        <!-- Contact Phone -->
                        <div class="form-field input-group">
                            <div class="input-icon">
                               
                            </div>
                            <input id="contact_phone" name="contact_phone" type="tel" required placeholder=" "
                                   class="w-full px-4 py-3 input-with-icon bg-transparent border rounded-[50px] border-gray-600 text-white placeholder-transparent focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200"
                                   value="{{ old('contact_phone') }}">
                            <label for="contact_phone" class="floating-label">Contact Phone *</label>
                            <div class="success-check absolute right-12 top-1/2 transform -translate-y-1/2">
                             
                            </div>
                        </div>
                    </div>
                    @error('company_name')
                        <p class="mt-2 text-sm text-red-400 slide-up">{{ $message }}</p>
                    @enderror
                    @error('contact_phone')
                        <p class="mt-2 text-sm text-red-400 slide-up">{{ $message }}</p>
                    @enderror

                    <!-- Company Bio -->
                    <div class="mt-6 form-field input-group">
                        <textarea id="company_bio" name="company_bio" rows="4" required placeholder=" "
                                  class="w-full px-4 py-3 bg-transparent border rounded-[20px] border-gray-600 text-white placeholder-transparent focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200 resize-none">{{ old('company_bio') }}</textarea>
                        <label for="company_bio" class="floating-label textarea-label">Company Bio *</label>
                    </div>
                    @error('company_bio')
                        <p class="mt-2 text-sm text-red-400 slide-up">{{ $message }}</p>
                    @enderror

                    <!-- Website (Optional) -->
                    <div class="mt-6 form-field input-group">
                        <div class="input-icon">
                           
                        </div>
                        <input id="website" name="website" type="url" placeholder=" "
                               class="w-full px-4 py-3 input-with-icon bg-transparent border rounded-[50px] border-gray-600 text-white placeholder-transparent focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200"
                               value="{{ old('website') }}">
                        <label for="website" class="floating-label">Website (Optional)</label>
                        <div class="success-check absolute right-12 top-1/2 transform -translate-y-1/2">
                           
                        </div>
                    </div>
                    @error('website')
                        <p class="mt-2 text-sm text-red-400 slide-up">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Terms Agreement -->
                <div class="form-step slide-up">
                    <div class="section-header">
                        <h3>Terms & Agreement</h3>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="relative">
                            <input id="terms" name="terms" type="checkbox" required
                                   class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-600 bg-transparent rounded transition-all duration-200">
                            <div class="absolute inset-0 rounded border-1 border-gray-600 pointer-events-none transition-colors duration-200" id="checkbox-border"></div>
                        </div>
                        <label for="terms" class="block text-sm text-gray-300 leading-relaxed">
                            I agree to the <a href="#" class="text-blue-400 hover:text-blue-300 underline transition-colors">Terms of Service</a>,
                            <a href="#" class="text-blue-400 hover:text-blue-300 underline transition-colors">Privacy Policy</a>, and understand that:
                            <ul class="mt-3 ml-4 text-xs text-gray-400 space-y-1">
                                <li class="flex items-start">
                                    <span class="text-blue-400 mr-2">•</span>
                                    <span>My account requires admin approval before I can create events</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-blue-400 mr-2">•</span>
                                    <span>A 15% platform fee applies to all ticket sales</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-blue-400 mr-2">•</span>
                                    <span>I will receive 85% of the revenue from my events</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-blue-400 mr-2">•</span>
                                    <span>All events must comply with platform guidelines</span>
                                </li>
                            </ul>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="slide-up">
                    <button type="submit" id="submitBtn"
                            class="w-full bg-white text-black font-medium py-4 px-4 rounded-[50px] focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 hover:bg-[#48ff91] transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
                        <span id="submitText">Submit Organizer Application</span>
                        <svg class="w-5 h-5 inline-block ml-2 hidden" id="loadingSpinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>

                <!-- Footer Links -->
                <div class="text-center mt-8 slide-up">
                    <p class="text-gray-400 mb-4">Already have an account?</p>
                    <a href="{{ route('login') }}"
                       class="inline-block border-2 border-[#052cff] text-[#052cff] font-medium py-3 px-8 rounded-[50px] hover:bg-[#48ff91] hover:border-[#48ff91] hover:text-white transition-all duration-200 transform hover:scale-105 mb-6">
                        Sign in here
                    </a>

                    <div class="space-y-3 mt-6">
                        <p class="text-sm text-gray-400">
                            Just want to attend events?
                            <a href="{{ route('register.client') }}" class="text-purple-400 hover:text-purple-300 underline transition-colors">
                                Register as Client
                            </a>
                        </p>
                        <p class="text-sm text-gray-500">
                            <a href="{{ route('welcome') }}" class="hover:text-gray-300 transition-colors">
                                ← Back to Home
                            </a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Progress tracking
        const inputs = document.querySelectorAll('input[required], textarea[required]');
        const progressFill = document.getElementById('progressFill');
        const formSteps = document.querySelectorAll('.form-step');
        
        function updateProgress() {
            const filledInputs = Array.from(inputs).filter(input => {
                if (input.type === 'checkbox') return input.checked;
                return input.value.trim() !== '';
            }).length;
            
            const progress = (filledInputs / inputs.length) * 100;
            progressFill.style.width = progress + '%';
            
            // Activate form steps progressively
            formSteps.forEach((step, index) => {
                if (index <= Math.floor(filledInputs / 3)) {
                    step.classList.add('active');
                }
            });
        }

        // Password toggle functionality
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                `;
            } else {
                field.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        // Password strength indicator
        function checkPasswordStrength(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password)
            };
            
            const strength = Object.values(requirements).filter(Boolean).length;
            const strengthPercentage = (strength / 4) * 100;
            
            document.getElementById('passwordStrength').style.width = strengthPercentage + '%';
            
            // Update requirements checklist
            Object.keys(requirements).forEach(req => {
                const element = document.querySelector(`[data-requirement="${req}"]`);
                if (element) {
                    element.style.color = requirements[req] ? '#22c55e' : '#9ca3af';
                    element.innerHTML = requirements[req] ? '✓ ' + element.innerHTML.substring(2) : '• ' + element.innerHTML.substring(2);
                }
            });
            
            return strength;
        }

        // Password confirmation matching
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchCheck = document.getElementById('password-match-check');
            const matchMessage = document.getElementById('password-match-message');
            
            if (confirmation && password) {
                if (password === confirmation) {
                    matchCheck.classList.add('show');
                    matchMessage.textContent = 'Passwords match ✓';
                    matchMessage.style.color = '#22c55e';
                } else {
                    matchCheck.classList.remove('show');
                    matchMessage.textContent = 'Passwords do not match';
                    matchMessage.style.color = '#ef4444';
                }
            } else {
                matchCheck.classList.remove('show');
                matchMessage.textContent = '';
            }
        }

        // Input validation and success indicators
        function validateInput(input) {
            const successCheck = input.parentElement.querySelector('.success-check');
            let isValid = false;
            
            if (input.type === 'email') {
                isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
            } else if (input.type === 'text' || input.type === 'tel') {
                isValid = input.value.trim().length >= 2;
            } else if (input.type === 'url') {
                isValid = input.value === '' || /^https?:\/\/.+/.test(input.value);
            } else if (input.tagName === 'TEXTAREA') {
                isValid = input.value.trim().length >= 10;
            }
            
            if (successCheck) {
                if (isValid) {
                    successCheck.classList.add('show');
                } else {
                    successCheck.classList.remove('show');
                }
            }
        }

        // Form submission handling
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            submitBtn.disabled = true;
            submitText.textContent = 'Submitting Application...';
            loadingSpinner.classList.remove('hidden');
            loadingSpinner.style.animation = 'spin 1s linear infinite';
        });

        // Event listeners
        inputs.forEach(input => {
            input.addEventListener('input', updateProgress);
            input.addEventListener('blur', () => validateInput(input));
            
            if (input.id === 'password') {
                input.addEventListener('input', (e) => {
                    checkPasswordStrength(e.target.value);
                    checkPasswordMatch();
                });
            }
            
            if (input.id === 'password_confirmation') {
                input.addEventListener('input', checkPasswordMatch);
            }
        });

        // Terms checkbox styling
        document.getElementById('terms').addEventListener('change', function(e) {
            const border = document.getElementById('checkbox-border');
            if (e.target.checked) {
                border.style.borderColor = '#3b82f6';
            } else {
                border.style.borderColor = '#6b7280';
            }
        });

        // Add CSS for spinner animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // Initialize progress
        updateProgress();
        
        // Add smooth scrolling for any form errors
        if (document.querySelector('.text-red-400')) {
            const firstError = document.querySelector('.text-red-400');
            const errorField = firstError.closest('.form-step');
            if (errorField) {
                errorField.classList.add('shake');
                errorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => errorField.classList.remove('shake'), 500);
            }
        }

        // Initialize form steps as active on load
        setTimeout(() => {
            formSteps.forEach(step => {
                step.classList.add('active');
            });
        }, 300);
    </script>
</body>
</html>