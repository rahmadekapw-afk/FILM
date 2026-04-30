@extends('layouts.app')

@section('title', 'MOVIX - Discover Amazing Movies')

@section('content')
    @include('components.hero', ['movie' => $featured ?? null, 'user' => null])

    <div class="container mx-auto px-4 py-8 space-y-24">
        @if(count($movies) === 0)
            <div class="flex flex-col items-center justify-center py-20">
                <i data-lucide="film" class="w-16 h-16 text-white/20 mb-4"></i>
                <p class="text-white/40 text-lg font-bold uppercase tracking-widest">No movies found</p>
                <p class="text-white/30 text-sm mt-2">Add movies via the <a href="{{ route('admin') }}" class="text-brand-primary hover:underline">admin panel</a></p>
            </div>
        @else
            @include('components.movie-section', [
                'title' => 'Trending Now',
                'movies' => $movies
            ])

            @include('components.movie-section', [
                'title' => 'Top Rated',
                'movies' => $movies->sortByDesc('rating')->values()
            ])

            @include('components.movie-section', [
                'title' => 'Recently Added',
                'movies' => $movies->reverse()->values()
            ])
        @endif
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    // GSAP scroll animations for sections
    const sections = gsap.utils.toArray('.reveal-section');
    sections.forEach((section) => {
        gsap.fromTo(section,
            { opacity: 0, y: 30 },
            {
                opacity: 1, y: 0, duration: 0.8,
                scrollTrigger: {
                    trigger: section,
                    start: "top 85%",
                    toggleActions: "play none none reverse"
                }
            }
        );

        const cards = section.querySelectorAll('.reveal-card');
        if (cards.length > 0) {
            gsap.fromTo(cards,
                { opacity: 0, y: 30, scale: 0.9 },
                {
                    opacity: 1, y: 0, scale: 1,
                    duration: 0.6,
                    stagger: 0.05,
                    ease: "back.out(1.7)",
                    scrollTrigger: {
                        trigger: section,
                        start: "top 80%",
                    }
                }
            );
        }
    });

    ScrollTrigger.refresh();
});
</script>
@endpush
