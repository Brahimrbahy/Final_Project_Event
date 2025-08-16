<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('I want to register as')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required onchange="toggleOrganizerFields()">
                <option value="">Select your role</option>
                <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client (I want to attend events)</option>
                <option value="organizer" {{ old('role') == 'organizer' ? 'selected' : '' }}>Organizer (I want to create events)</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Organizer Profile Fields (shown only when organizer is selected) -->
        <div id="organizer-fields" class="mt-4 space-y-4" style="display: {{ old('role') == 'organizer' ? 'block' : 'none' }};">
            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                <h4 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-3">Organizer Information</h4>
                <p class="text-sm text-blue-700 dark:text-blue-300 mb-4">Please provide your company details. Your application will be reviewed by our admin team.</p>

                <!-- Company Name -->
                <div class="mb-4">
                    <x-input-label for="company_name" :value="__('Company Name')" />
                    <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" placeholder="e.g., ABC Events Company" />
                    <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                </div>

                <!-- Contact Info -->
                <div class="mb-4">
                    <x-input-label for="contact_info" :value="__('Contact Phone')" />
                    <x-text-input id="contact_info" class="block mt-1 w-full" type="text" name="contact_info" :value="old('contact_info')" placeholder="e.g., +1 (555) 123-4567" />
                    <x-input-error :messages="$errors->get('contact_info')" class="mt-2" />
                </div>

                <!-- Bio -->
                <div class="mb-4">
                    <x-input-label for="bio" :value="__('Company Bio')" />
                    <textarea id="bio" name="bio" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Tell us about your company and event organizing experience...">{{ old('bio') }}</textarea>
                    <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                </div>

                <!-- Website (Optional) -->
                <div>
                    <x-input-label for="website" :value="__('Website (Optional)')" />
                    <x-text-input id="website" class="block mt-1 w-full" type="url" name="website" :value="old('website')" placeholder="https://yourcompany.com" />
                    <x-input-error :messages="$errors->get('website')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4" id="register-btn">
                <span id="register-text">{{ __('Register') }}</span>
                <span id="register-organizer-text" style="display: none;">{{ __('Submit Application') }}</span>
            </x-primary-button>
        </div>
    </form>

    <!-- JavaScript for dynamic form behavior -->
    <script>
        function toggleOrganizerFields() {
            const roleSelect = document.getElementById('role');
            const organizerFields = document.getElementById('organizer-fields');
            const registerText = document.getElementById('register-text');
            const registerOrganizerText = document.getElementById('register-organizer-text');
            const companyName = document.getElementById('company_name');
            const contactInfo = document.getElementById('contact_info');
            const bio = document.getElementById('bio');

            if (roleSelect.value === 'organizer') {
                organizerFields.style.display = 'block';
                registerText.style.display = 'none';
                registerOrganizerText.style.display = 'inline';

                // Make organizer fields required
                companyName.required = true;
                contactInfo.required = true;
                bio.required = true;

                // Smooth scroll to show the fields
                setTimeout(() => {
                    organizerFields.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            } else {
                organizerFields.style.display = 'none';
                registerText.style.display = 'inline';
                registerOrganizerText.style.display = 'none';

                // Remove required from organizer fields
                companyName.required = false;
                contactInfo.required = false;
                bio.required = false;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleOrganizerFields();
        });
    </script>
</x-guest-layout>
