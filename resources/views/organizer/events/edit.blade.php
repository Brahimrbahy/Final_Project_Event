@extends('layouts.dashbord')
@section('content')
    <div class="flex h-screen bg-gray-100">
            <x-dashboard-sidebar role="organizer" :current-route="request()->route()->getName()" />

        <div class="p-6 flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('organizer.events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Event Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" 
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
                                      required>{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category" id="category" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                    required>
                                <option value="">Select a category</option>
                                <option value="Concerts" {{ old('category', $event->category) == 'Concerts' ? 'selected' : '' }}>🎵 Concerts</option>
                                <option value="Festivals" {{ old('category', $event->category) == 'Festivals' ? 'selected' : '' }}>🎪 Festivals</option>
                                <option value="Theatre" {{ old('category', $event->category) == 'Theatre' ? 'selected' : '' }}>🎭 Theatre</option>
                                <option value="Sports" {{ old('category', $event->category) == 'Sports' ? 'selected' : '' }}>⚽ Sports</option>
                                <option value="Cinema" {{ old('category', $event->category) == 'Cinema' ? 'selected' : '' }}>🎬 Cinema</option>
                                <option value="Business" {{ old('category', $event->category) == 'Business' ? 'selected' : '' }}>💼 Business</option>
                                <option value="Technology" {{ old('category', $event->category) == 'Technology' ? 'selected' : '' }}>💻 Technology</option>
                                <option value="Arts & Culture" {{ old('category', $event->category) == 'Arts & Culture' ? 'selected' : '' }}>🎨 Arts & Culture</option>
                                <option value="Education" {{ old('category', $event->category) == 'Education' ? 'selected' : '' }}>📚 Education</option>
                                <option value="Food & Drink" {{ old('category', $event->category) == 'Food & Drink' ? 'selected' : '' }}>🍽️ Food & Drink</option>
                                <option value="Health & Wellness" {{ old('category', $event->category) == 'Health & Wellness' ? 'selected' : '' }}>🏃 Health & Wellness</option>
                                <option value="Other" {{ old('category', $event->category) == 'Other' ? 'selected' : '' }}>📋 Other</option>
                            </select>
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
                                    <option value="free" {{ old('type', $event->type) == 'free' ? 'selected' : '' }}>Free Event</option>
                                    <option value="paid" {{ old('type', $event->type) == 'paid' ? 'selected' : '' }}>Paid Event</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="price-field" style="display: {{ old('type', $event->type) == 'paid' ? 'block' : 'none' }};">
                                <label for="price" class="block text-sm font-medium text-gray-700">Ticket Price ($)</label>
                                <input type="number" name="price" id="price" value="{{ old('price', $event->price) }}" 
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
                            <input type="datetime-local" name="start_date" id="start_date"
                                   value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}"
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
                                <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                       required>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Full Address (Optional)</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $event->address) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Max Tickets -->
                        <div>
                            <label for="max_tickets" class="block text-sm font-medium text-gray-700">Maximum Tickets (Optional)</label>
                            <input type="number" name="max_tickets" id="max_tickets" value="{{ old('max_tickets', $event->max_tickets) }}" 
                                   min="1"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Leave empty for unlimited tickets</p>
                            @error('max_tickets')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Image -->
                        @if($event->image_path)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Image</label>
                                <div class="mt-1">
                                    <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}" 
                                         class="w-32 h-32 object-cover rounded-lg">
                                </div>
                            </div>
                        @endif

                        <!-- Event Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">
                                {{ $event->image_path ? 'Replace Event Image' : 'Event Image' }}
                            </label>
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
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('terms_conditions', $event->terms_conditions) }}</textarea>
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
                                Update Event
                            </button>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Note:</strong> 
                                        @if($event->approved)
                                            This event is already approved and visible to the public. Changes may require re-approval.
                                        @else
                                            This event is pending approval. Changes will be reviewed along with the original submission.
                                        @endif
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

        // Set minimum date validation
        document.addEventListener('DOMContentLoaded', function() {
            // Any additional date validation can be added here
        });
    </script>
</div>
@endsection
