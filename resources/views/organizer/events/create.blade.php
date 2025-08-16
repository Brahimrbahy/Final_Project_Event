<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Feature Overview -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-blue-900 mb-3">Create Your Event - Full Feature Set</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
                    <div>
                        <h4 class="font-medium mb-2">✨ Event Details</h4>
                        <ul class="space-y-1">
                            <li>• Custom title and detailed description</li>
                            <li>• 12 categories: Concerts, Festivals, Theatre, Sports, Cinema, etc.</li>
                            <li>• Free or paid events with custom pricing</li>
                            <li>• Date, time, and location settings</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-medium mb-2">🎯 Advanced Features</h4>
                        <ul class="space-y-1">
                            <li>• Upload event images (JPG, PNG, GIF)</li>
                            <li>• Set ticket limits or unlimited tickets</li>
                            <li>• Custom terms and conditions</li>
                            <li>• Admin approval workflow</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Event Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Event Description</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Event Category</label>
                            <select name="category" id="category"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                <option value="">Select a category</option>
                                <option value="Concerts" {{ old('category') == 'Concerts' ? 'selected' : '' }}>🎵 Concerts</option>
                                <option value="Festivals" {{ old('category') == 'Festivals' ? 'selected' : '' }}>🎪 Festivals</option>
                                <option value="Theatre" {{ old('category') == 'Theatre' ? 'selected' : '' }}>🎭 Theatre</option>
                                <option value="Sports" {{ old('category') == 'Sports' ? 'selected' : '' }}>⚽ Sports</option>
                                <option value="Cinema" {{ old('category') == 'Cinema' ? 'selected' : '' }}>🎬 Cinema</option>
                                <option value="Business" {{ old('category') == 'Business' ? 'selected' : '' }}>💼 Business</option>
                                <option value="Technology" {{ old('category') == 'Technology' ? 'selected' : '' }}>💻 Technology</option>
                                <option value="Arts & Culture" {{ old('category') == 'Arts & Culture' ? 'selected' : '' }}>🎨 Arts & Culture</option>
                                <option value="Education" {{ old('category') == 'Education' ? 'selected' : '' }}>📚 Education</option>
                                <option value="Food & Drink" {{ old('category') == 'Food & Drink' ? 'selected' : '' }}>🍽️ Food & Drink</option>
                                <option value="Health & Wellness" {{ old('category') == 'Health & Wellness' ? 'selected' : '' }}>🏃 Health & Wellness</option>
                                <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>📋 Other</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Choose the category that best describes your event</p>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event Type and Price -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Event Type</label>
                                <select name="type" id="type" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                        required onchange="togglePriceField()">
                                    <option value="free" {{ old('type') == 'free' ? 'selected' : '' }}>Free Event</option>
                                    <option value="paid" {{ old('type') == 'paid' ? 'selected' : '' }}>Paid Event</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="price-field" style="display: {{ old('type') == 'paid' ? 'block' : 'none' }};">
                                <label for="price" class="block text-sm font-medium text-gray-700">Ticket Price ($)</label>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" 
                                       step="0.01" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Event Date & Time</label>
                            <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Venue/Location</label>
                                <input type="text" name="location" id="location" value="{{ old('location') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                       required>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Full Address (Optional)</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Max Tickets -->
                        <div>
                            <label for="max_tickets" class="block text-sm font-medium text-gray-700">Maximum Tickets (Optional)</label>
                            <input type="number" name="max_tickets" id="max_tickets" value="{{ old('max_tickets') }}" 
                                   min="1"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Leave empty for unlimited tickets</p>
                            @error('max_tickets')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Event Image</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-sm text-gray-500">Upload an image for your event (JPG, PNG, GIF - Max 2MB)</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terms and Conditions -->
                        <div>
                            <label for="terms_conditions" class="block text-sm font-medium text-gray-700">Terms & Conditions (Optional)</label>
                            <textarea name="terms_conditions" id="terms_conditions" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('terms_conditions') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Any specific terms or conditions for this event</p>
                            @error('terms_conditions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('organizer.events') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                                Create Event
                            </button>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>Note:</strong> Your event will need admin approval before it becomes visible to the public. 
                                        You'll be notified once it's approved.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePriceField() {
            const typeSelect = document.getElementById('type');
            const priceField = document.getElementById('price-field');
            const priceInput = document.getElementById('price');
            
            if (typeSelect.value === 'paid') {
                priceField.style.display = 'block';
                priceInput.required = true;
            } else {
                priceField.style.display = 'none';
                priceInput.required = false;
                priceInput.value = '';
            }
        }

        // Set minimum date to today
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

            document.getElementById('start_date').min = minDateTime;
        });
    </script>
</x-app-layout>
