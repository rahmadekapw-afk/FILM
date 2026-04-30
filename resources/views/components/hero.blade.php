@props(['movie' => null, 'user' => null])
@php
    $authUser = $user ?? auth()->user();
@endphp

@if(!$movie)
    <div class="h-[90vh] bg-bg-dark"></div>
@else
    <section class="hero-container relative h-[90vh] w-full flex items-center overflow-hidden">

        <div class="absolute inset-0 z-0">
            <img
                id="hero-img"
                src="{{ $movie['image'] ?? 'https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?q=80&w=2070&auto=format&fit=crop' }}"
                alt="{{ $movie['title'] }}"
                class="w-full h-[130%] object-cover absolute top-0 left-0"
            />
            <div class="absolute inset-0 bg-gradient-to-r from-bg-dark via-bg-dark/60 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 h-64 bg-gradient-to-t from-bg-dark to-transparent"></div>
        </div>

        <div class="container mx-auto px-4 md:px-6 relative z-10 pt-20">
            <div id="hero-content" class="max-w-2xl space-y-6 opacity-0 translate-y-5">
                <div class="flex flex-col gap-4">
                    @if($authUser)
                        <p id="hero-welcome" class="text-brand-primary font-black uppercase tracking-[0.3em] text-sm opacity-0 -translate-x-5">
                            Welcome back, {{ $authUser->name }}
                        </p>
                    @endif
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md w-fit px-3 py-1 rounded-full border border-white/20">
                        <i data-lucide="star" class="w-4 h-4 fill-brand-primary text-brand-primary"></i>
                        <span class="text-xs font-bold uppercase tracking-wider text-white">Featured Movie</span>
                    </div>
                </div>

                <h1 class="text-5xl md:text-7xl font-black text-white leading-tight uppercase overflow-hidden">
                    @php
                        $titlePart = trim(explode('(', $movie['title'])[0] ?? $movie['title']);
                        $chars = mb_str_split($titlePart);
                    @endphp
                    @foreach($chars as $char)
                        <span class="inline-block hero-char opacity-0 translate-y-full">{!! $char === ' ' ? '&nbsp;' : e($char) !!}</span>
                    @endforeach
                    <br />
                    <span id="hero-genre" class="text-brand-primary inline-block opacity-0 -translate-x-5">
                        {{ $movie['genres'][0] ?? 'CINEMATIC' }}
                    </span>
                </h1>

                <p class="text-lg text-white/70 leading-relaxed font-medium">
                    Experience the next generation of storytelling with {{ trim(explode('(', $movie['title'])[0]) }}. Join a community of millions and discover exclusive content.
                </p>

                <div class="flex items-center gap-6 pt-4">
                    <a href="{{ route('watch', $movie['id']) }}"
                       class="flex items-center gap-2 bg-brand-primary hover:bg-brand-secondary text-white px-8 py-5 rounded-2xl font-black text-lg shadow-[0_15px_30px_-10px_rgba(229,9,20,0.4)] transition-all active:scale-95 magnetic-btn">
                        <i data-lucide="play" class="w-6 h-6 fill-white"></i>
                        <span>Watch Now</span>
                    </a>

                    <button class="flex items-center gap-2 bg-white/5 hover:bg-white/10 backdrop-blur-xl text-white px-8 py-5 rounded-2xl font-black text-lg border border-white/10 transition-all magnetic-btn">
                        <i data-lucide="info" class="w-6 h-6"></i>
                        <span>More Info</span>
                    </button>
                </div>

                <div class="flex items-center gap-8 pt-6">
                    <div class="text-center">
                        <p class="text-2xl font-black text-white">{{ $movie['rating'] }}</p>
                        <p class="text-xs font-bold text-white/50 uppercase tracking-widest">Rating</p>
                    </div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div class="text-center">
                        <p class="text-2xl font-black text-white">{{ $movie['year'] }}</p>
                        <p class="text-xs font-bold text-white/50 uppercase tracking-widest">Release</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden xl:block absolute right-20 top-1/2 -translate-y-1/2 space-y-4">
            @for ($i = 0; $i < 3; $i++)
                <div class="floating-badge bg-white/5 backdrop-blur-xl p-4 rounded-3xl border border-white/10 w-48 shadow-2xl" style="animation-delay: {{ $i * 0.5 }}s">
                    <div class="h-24 bg-white/10 rounded-2xl mb-3 animate-pulse"></div>
                    <div class="h-4 bg-white/20 rounded-full w-3/4 mb-2"></div>
                    <div class="h-3 bg-white/10 rounded-full w-1/2"></div>
                </div>
            @endfor
        </div>
    </section>

    @push('scripts')
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .floating-badge {
            animation: float 3s infinite ease-in-out;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Paralax Background
            const heroImg = document.getElementById('hero-img');
            if (heroImg && window.ScrollTrigger) {
                gsap.to(heroImg, {
                    yPercent: 30,
                    ease: "none",
                    scrollTrigger: {
                        trigger: ".hero-container",
                        start: "top top",
                        end: "bottom top",
                        scrub: true
                    }
                });
            }
            const tl = gsap.timeline();
            tl.to("#hero-content", { opacity: 1, y: 0, duration: 0.8, ease: "power2.out" })
              @if($authUser)
              .to("#hero-welcome", { opacity: 1, x: 0, duration: 0.5 }, "-=0.4")
              @endif
              .to(".hero-char", {
                  opacity: 1,
                  y: 0,
                  duration: 0.5,
                  stagger: 0.03,
                  ease: "back.out(1.7)"
              }, "-=0.2")
              .to("#hero-genre", { opacity: 1, x: 0, duration: 0.5 }, "-=0.2");

            const magneticBtns = document.querySelectorAll('.magnetic-btn');
            magneticBtns.forEach(btn => {
                btn.addEventListener('mousemove', (e) => {
                    const rect = btn.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    gsap.to(btn, { x: x * 0.2, y: y * 0.2, duration: 0.3 });
                });
                btn.addEventListener('mouseleave', () => {
                    gsap.to(btn, { x: 0, y: 0, duration: 1, ease: "elastic.out(1, 0.3)" });
                });
            });
        });
    </script>
    @endpush
@endif
