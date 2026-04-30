@props(['movie'])

<a href="{{ route('watch', $movie['id']) }}" class="group relative bg-bg-card rounded-2xl overflow-hidden shadow-md hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.5)] transition-all duration-300 border border-transparent hover:border-brand-primary/30 reveal-card hover:scale-105 hover:-translate-y-1 block">
    <!-- Image Container -->
    <div class="aspect-[2/3] relative overflow-hidden rounded-xl">
        <img
            src="{{ $movie['image'] ?? 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?q=80&w=400&auto=format&fit=crop' }}"
            alt="{{ $movie['title'] }}"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
            loading="lazy"
        />

        <!-- Hover Overlay -->
        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
            <div class="flex flex-col items-center gap-2">
                <div class="bg-brand-primary p-3 rounded-full shadow-lg shadow-brand-primary/40 scale-0 group-hover:scale-100 transition-transform duration-300">
                    <i data-lucide="play" class="w-5 h-5 fill-white text-white"></i>
                </div>
                @if($movie['video'])
                    <span class="text-[9px] font-black uppercase tracking-widest text-white/70 bg-black/50 px-2 py-0.5 rounded-full">Watch Now</span>
                @else
                    <span class="text-[9px] font-black uppercase tracking-widest text-white/50 bg-black/50 px-2 py-0.5 rounded-full">No Video</span>
                @endif
            </div>
        </div>

        <!-- Video indicator badge -->
        @if($movie['video'])
            <div class="absolute bottom-3 right-3 bg-brand-primary/90 backdrop-blur-sm p-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                <i data-lucide="video" class="w-3 h-3 text-white"></i>
            </div>
        @endif

        <!-- Rating Badge -->
        <div class="absolute top-3 left-3 flex items-center gap-1 bg-black/60 backdrop-blur-md px-2 py-1 rounded-xl border border-white/10">
            <i data-lucide="star" class="w-3 h-3 fill-brand-primary text-brand-primary"></i>
            <span class="text-[10px] font-bold text-white">{{ $movie['rating'] ?: 'N/A' }}</span>
        </div>
    </div>

    <!-- Text Info dihilangkan agar mirip desain poster Vidio -->
</a>
