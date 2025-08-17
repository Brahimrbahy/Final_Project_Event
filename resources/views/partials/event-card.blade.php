<!-- Event Card for Home Page with iOS Glassmorphism Style -->
<div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-2xl overflow-hidden shadow-2xl hover:shadow-[0_32px_64px_rgba(0,0,0,0.3)] hover:bg-white/15 transition-all duration-500 transform hover:-translate-y-2 event-card relative before:absolute before:inset-0 before:rounded-2xl before:p-px before:bg-gradient-to-b before:from-white/20 before:to-transparent before:mask-composite-[subtract] before:mask-[linear-gradient(#fff_0_0)] group">
    <!-- Event Image -->
    <div class="relative">
        @if($event->image_path)
            <img src="{{ Storage::url($event->image_path) }}" 
                 alt="{{ $event->title }}" 
                 class="w-full h-48 object-cover">
        @else
            <div class="w-full h-48 bg-gradient-to-br from-blue-500/80 via-purple-600/80 to-pink-500/80 backdrop-blur-sm flex items-center justify-center relative">
                <!-- Subtle glass overlay -->
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <svg class="w-16 h-16 text-white/70 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif
        
        <!-- Category Badge -->
        <div class="absolute top-3 left-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-md bg-blue-500/80 text-white border border-blue-400/30 shadow-lg">
                {{ $event->category }}
            </span>
        </div>
        
        <!-- Price Badge -->
        <div class="absolute top-3 right-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-md border shadow-lg
                {{ $event->type === 'free' ? 'bg-green-500/80 text-white border-green-400/30' : 'bg-purple-500/80 text-white border-purple-400/30' }}">
                {{ $event->type === 'free' ? 'Gratuit' : number_format($event->price, 2) . ' €' }}
            </span>
        </div>

        <!-- Sold Out Overlay -->
        @if($event->max_tickets && $event->tickets_sold >= $event->max_tickets)
            <div class="absolute inset-0 backdrop-blur-sm bg-black/40 flex items-center justify-center">
                <span class="bg-red-500/90 backdrop-blur-md text-white px-6 py-3 rounded-2xl font-bold text-lg border border-red-400/30 shadow-2xl">
                    COMPLET
                </span>
            </div>
        @endif
    </div>

    <!-- Event Content -->
    <div class="p-6 relative">
        <!-- Subtle inner glow -->
        <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent rounded-b-2xl pointer-events-none"></div>
        
        <div class="relative z-10">
            <!-- Event Title -->
            <h3 class="text-lg font-semibold text-white/95 mb-2 line-clamp-2 drop-shadow-sm">
                <a href="{{ route('events.show', $event) }}" class="hover:text-blue-300 transition-colors duration-300">
                    {{ $event->title }}
                </a>
            </h3>

            <!-- Event Description -->
            <p class="text-white/70 text-sm mb-4 line-clamp-2 drop-shadow-sm">
                {{ Str::limit($event->description, 100) }}
            </p>

            <!-- Event Details -->
            <div class="space-y-2 mb-4">
                <!-- Date -->
                <div class="flex items-center text-sm text-white/80">
                    <svg class="w-4 h-4 mr-2 text-blue-300/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                    </svg>
                    {{ $event->start_date->format('d M Y à H:i') }}
                </div>

                <!-- Location -->
                <div class="flex items-center text-sm text-white/80">
                    <svg class="w-4 h-4 mr-2 text-blue-300/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ Str::limit($event->location, 30) }}
                </div>

                <!-- Organizer -->
                <div class="flex items-center text-sm text-white/80">
                    <svg class="w-4 h-4 mr-2 text-blue-300/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    par {{ $event->organizer->name }}
                </div>

                <!-- Tickets Available -->
                @if($event->max_tickets)
                    <div class="flex items-center text-sm text-white/80">
                        <svg class="w-4 h-4 mr-2 text-blue-300/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 21a2 2 0 01-2-2v-3a1 1 0 011-1h1a1 1 0 011 1v3a2 2 0 01-2 2H5z"></path>
                        </svg>
                        {{ $event->max_tickets - $event->tickets_sold }} places disponibles
                    </div>
                @endif
            </div>

            <!-- Action Button -->
            <div class="flex items-center justify-between">
                <a href="{{ route('events.show', $event) }}" 
                   class="backdrop-blur-md bg-blue-500/80 hover:bg-blue-400/90 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center space-x-2 border border-blue-400/30 shadow-lg hover:shadow-xl hover:scale-105 group-hover:bg-blue-400/80">
                    <span>Voir Détails</span>
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                
                @if($event->start_date > now())
                    <span class="text-xs text-green-300/90 font-semibold backdrop-blur-sm bg-green-500/20 px-2 py-1 rounded-lg border border-green-400/20">À venir</span>
                @else
                    <span class="text-xs text-gray-300/70 font-semibold backdrop-blur-sm bg-gray-500/20 px-2 py-1 rounded-lg border border-gray-400/20">Terminé</span>
                @endif
            </div>
        </div>
    </div>
</div>