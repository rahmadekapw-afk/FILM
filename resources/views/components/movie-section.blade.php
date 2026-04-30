@props(['title', 'movies' => []])

<section class="reveal-section space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-black uppercase tracking-tight flex items-center gap-3">
            <div class="w-1.5 h-8 bg-brand-primary rounded-full shadow-[0_0_15px_rgba(229,9,20,0.5)]"></div>
            {{ $title }}
        </h2>
        <div class="h-px flex-1 mx-8 bg-gradient-to-r from-white/10 to-transparent"></div>
        <button class="flex items-center gap-1 text-sm font-bold text-white/50 hover:text-brand-primary transition-colors uppercase tracking-widest">
            <span>View All</span>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </button>
    </div>

    <div class="relative group/slider">
        <!-- Horizontal Scroll Container -->
        <div class="flex overflow-x-auto gap-4 sm:gap-5 pb-4 snap-x snap-mandatory scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
            @foreach($movies as $movie)
                <div class="snap-start flex-shrink-0 w-[140px] sm:w-[160px] md:w-[180px] lg:w-[200px]">
                    @include('components.movie-card', ['movie' => $movie])
                </div>
            @endforeach
        </div>
        
        <!-- Fading Edges & Scroll Buttons (Optional but good for UX) -->
        <div class="absolute top-0 right-0 bottom-4 w-24 bg-gradient-to-l from-bg-dark to-transparent pointer-events-none hidden md:block"></div>
        <div class="absolute top-0 left-0 bottom-4 w-24 bg-gradient-to-r from-bg-dark to-transparent pointer-events-none hidden md:block"></div>
    </div>
</section>
