<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $movie['cleanTitle'] ?? $movie['title'] }} — MOVIX</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            --color-brand-primary: #e50914;
            --color-brand-secondary: #ff0000;
            --color-bg-dark: #080808;
            --color-bg-card: #141414;
        }
        @layer base { body { @apply bg-bg-dark text-white font-sans antialiased; } }
        .video-wrapper:hover .video-controls { opacity: 1; }
        .video-controls { transition: opacity 0.3s ease; }
        .progress-bar::-webkit-slider-thumb { -webkit-appearance: none; width: 14px; height: 14px; border-radius: 50%; background: #e50914; cursor: pointer; }
        .progress-bar::-moz-range-thumb { width: 14px; height: 14px; border-radius: 50%; background: #e50914; border: none; cursor: pointer; }
        .volume-bar::-webkit-slider-thumb { -webkit-appearance: none; width: 12px; height: 12px; border-radius: 50%; background: white; cursor: pointer; }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
</head>
<body class="bg-bg-dark text-white antialiased min-h-screen">

    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-6 py-4 bg-gradient-to-b from-black/80 to-transparent">
        <div class="flex items-center gap-6">
            <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                <div class="bg-brand-primary p-1 rounded-md group-hover:bg-brand-secondary transition-colors">
                    <i data-lucide="play" class="w-5 h-5 fill-white text-white"></i>
                </div>
                <span class="text-xl font-black tracking-tighter">MOVIX</span>
            </a>
            <a href="{{ url()->previous() }}" class="flex items-center gap-2 text-white/60 hover:text-white transition-colors text-sm font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Back</span>
            </a>
        </div>
        <div class="flex items-center gap-2 text-sm text-white/50 font-medium">
            <i data-lucide="film" class="w-4 h-4"></i>
            <span class="truncate max-w-[300px]">{{ $movie['cleanTitle'] ?? $movie['title'] }}</span>
        </div>
    </nav>

    <div class="pt-16">
        <!-- VIDEO PLAYER SECTION -->
        <div class="relative bg-black">
            <div class="video-wrapper relative w-full max-w-[1400px] mx-auto" id="video-wrapper">

                @if($movie['video'])
                    <!-- Actual Video Player -->
                    <video
                        id="main-video"
                        class="w-full aspect-video bg-black"
                        preload="metadata"
                        poster="{{ $movie['image'] ?? '' }}"
                    >
                        <source src="{{ $movie['video'] }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <!-- No Video Placeholder -->
                    <div class="w-full aspect-video bg-gradient-to-br from-neutral-900 to-black flex flex-col items-center justify-center gap-6"
                         style="background-image: url('{{ $movie['image'] }}'); background-size: cover; background-position: center;">
                        <div class="absolute inset-0 bg-black/75"></div>
                        <div class="relative z-10 text-center space-y-4">
                            <div class="w-24 h-24 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center mx-auto border border-white/20">
                                <i data-lucide="video-off" class="w-10 h-10 text-white/50"></i>
                            </div>
                            <p class="text-white/60 font-bold text-xl uppercase tracking-widest">Video Not Available</p>
                            <p class="text-white/30 text-sm">Upload a video from the <a href="{{ url('/admin') }}" class="text-brand-primary hover:underline">admin panel</a></p>
                        </div>
                    </div>
                @endif

                @if($movie['video'])
                <!-- Custom Video Controls -->
                <div class="video-controls absolute bottom-0 left-0 right-0 opacity-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent p-4 pt-16" id="video-controls">
                    <!-- Progress Bar -->
                    <div class="mb-3 flex items-center gap-3">
                        <span id="current-time" class="text-xs font-bold text-white/60 w-10 text-right tabular-nums">0:00</span>
                        <div class="flex-1 relative group/progress">
                            <input type="range" id="progress-bar" class="progress-bar w-full h-1 bg-white/20 rounded-full appearance-none cursor-pointer accent-brand-primary" value="0" min="0" max="100" step="0.1"/>
                            <div id="progress-fill" class="absolute top-0 left-0 h-1 bg-brand-primary rounded-full pointer-events-none" style="width:0%"></div>
                        </div>
                        <span id="duration-time" class="text-xs font-bold text-white/60 w-10 tabular-nums">0:00</span>
                    </div>

                    <!-- Controls Row -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <!-- Rewind -->
                            <button onclick="skipTime(-10)" class="p-2 text-white/70 hover:text-white transition-colors" title="Rewind 10s">
                                <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                            </button>
                            <!-- Play/Pause -->
                            <button id="play-btn" onclick="togglePlay()" class="w-12 h-12 bg-brand-primary hover:bg-brand-secondary rounded-full flex items-center justify-center transition-all shadow-lg shadow-brand-primary/30 active:scale-95">
                                <i id="play-icon" data-lucide="play" class="w-5 h-5 fill-white text-white"></i>
                            </button>
                            <!-- Forward -->
                            <button onclick="skipTime(10)" class="p-2 text-white/70 hover:text-white transition-colors" title="Forward 10s">
                                <i data-lucide="rotate-cw" class="w-5 h-5"></i>
                            </button>
                            <!-- Volume -->
                            <div class="flex items-center gap-2">
                                <button id="mute-btn" onclick="toggleMute()" class="p-2 text-white/70 hover:text-white transition-colors">
                                    <i id="volume-icon" data-lucide="volume-2" class="w-5 h-5"></i>
                                </button>
                                <input type="range" id="volume-bar" class="volume-bar w-20 h-1 bg-white/20 rounded-full appearance-none cursor-pointer" min="0" max="1" step="0.05" value="1"/>
                            </div>
                            <!-- Time display -->
                            <span id="time-display" class="text-sm font-bold text-white/50 tabular-nums hidden sm:block">0:00 / 0:00</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Playback Speed -->
                            <select id="speed-select" onchange="changeSpeed(this.value)" class="bg-white/10 text-white text-xs font-bold px-3 py-1.5 rounded-xl border border-white/20 backdrop-blur-sm cursor-pointer appearance-none text-center">
                                <option value="0.5">0.5x</option>
                                <option value="0.75">0.75x</option>
                                <option value="1" selected>1x</option>
                                <option value="1.25">1.25x</option>
                                <option value="1.5">1.5x</option>
                                <option value="2">2x</option>
                            </select>
                            <!-- Fullscreen -->
                            <button onclick="toggleFullscreen()" class="p-2 text-white/70 hover:text-white transition-colors" title="Fullscreen">
                                <i id="fullscreen-icon" data-lucide="maximize" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Center Play/Pause Ripple (shown on click) -->
                <div id="click-ripple" class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-0">
                    <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i id="ripple-icon" data-lucide="play" class="w-10 h-10 fill-white text-white"></i>
                    </div>
                </div>

                <!-- Click to play/pause on video area -->
                <div class="absolute inset-0 cursor-pointer" onclick="togglePlay(); showRipple()" id="video-click-area" style="bottom: 80px;"></div>
                @endif
            </div>
        </div>

        <!-- MOVIE INFO & RELATED SECTION -->
        <div class="max-w-[1400px] mx-auto px-6 py-10 grid grid-cols-1 xl:grid-cols-3 gap-12">

            <!-- Left: Movie Details -->
            <div class="xl:col-span-2 space-y-8">

                <!-- Title + Meta -->
                <div>
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="text-xs font-black uppercase tracking-widest text-brand-primary bg-brand-primary/10 border border-brand-primary/30 px-3 py-1 rounded-full">
                            {{ $movie['year'] }}
                        </span>
                        @if($movie['duration'] !== 'N/A')
                            <span class="text-xs font-bold text-white/50 bg-white/5 border border-white/10 px-3 py-1 rounded-full flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                {{ $movie['duration'] }}
                            </span>
                        @endif
                        @if($movie['rating'])
                            <span class="text-xs font-bold text-yellow-400 bg-yellow-400/10 border border-yellow-400/20 px-3 py-1 rounded-full flex items-center gap-1.5">
                                <i data-lucide="star" class="w-3 h-3 fill-yellow-400"></i>
                                {{ $movie['rating'] }} / 5
                            </span>
                        @endif
                        @if($movie['video'])
                            <span class="text-xs font-bold text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 px-3 py-1 rounded-full flex items-center gap-1.5">
                                <i data-lucide="video" class="w-3 h-3"></i>
                                Video Available
                            </span>
                        @endif
                    </div>

                    <h1 class="text-4xl md:text-5xl font-black text-white leading-tight mb-2 uppercase tracking-tight">
                        {{ $movie['cleanTitle'] ?? $movie['title'] }}
                    </h1>

                    <!-- Genres -->
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach($movie['genres'] as $genre)
                            <span class="text-sm font-bold text-white/60 bg-white/5 px-4 py-1.5 rounded-full border border-white/10 hover:border-brand-primary/40 hover:text-brand-primary transition-colors cursor-pointer">
                                {{ $genre }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-3">
                    <h3 class="text-xs font-black uppercase tracking-widest text-white/30">Synopsis</h3>
                    <p class="text-white/70 text-base leading-relaxed font-medium">
                        {{ $movie['description'] }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-4">
                    @if($movie['video'])
                        <button onclick="document.getElementById('main-video').scrollIntoView({behavior:'smooth'}); setTimeout(()=>togglePlay(),500)"
                            class="flex items-center gap-3 bg-brand-primary hover:bg-brand-secondary text-white px-8 py-4 rounded-2xl font-black text-base shadow-xl shadow-brand-primary/20 transition-all active:scale-95">
                            <i data-lucide="play" class="w-5 h-5 fill-white"></i>
                            Watch Now
                        </button>
                    @endif
                    <button class="flex items-center gap-3 bg-white/10 hover:bg-white/15 text-white px-8 py-4 rounded-2xl font-black text-base border border-white/10 transition-all active:scale-95">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        Add to List
                    </button>
                    <button class="flex items-center gap-3 bg-white/10 hover:bg-white/15 text-white px-6 py-4 rounded-2xl font-black text-base border border-white/10 transition-all active:scale-95">
                        <i data-lucide="thumbs-up" class="w-5 h-5"></i>
                        Like
                    </button>
                </div>
            </div>

            <!-- Right: Quick Info Panel -->
            <div class="space-y-6">
                <div class="bg-bg-card border border-white/5 rounded-3xl p-6 space-y-5">
                    <h3 class="text-xs font-black uppercase tracking-widest text-white/30">Movie Details</h3>
                    @foreach([['label' => 'Title', 'value' => $movie['title']], ['label' => 'Year', 'value' => $movie['year']], ['label' => 'Duration', 'value' => $movie['duration']], ['label' => 'Rating', 'value' => ($movie['rating'] ?: 'Not rated') . ($movie['rating'] ? ' / 5' : '')], ['label' => 'Genres', 'value' => implode(', ', $movie['genres'])]] as $detail)
                        <div class="flex justify-between gap-4 border-b border-white/5 pb-4 last:border-0 last:pb-0">
                            <span class="text-xs font-black uppercase tracking-widest text-white/30">{{ $detail['label'] }}</span>
                            <span class="text-sm font-semibold text-white/80 text-right">{{ $detail['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- RELATED MOVIES -->
        @if($related->count() > 0)
            <div class="max-w-[1400px] mx-auto px-6 pb-20">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-1.5 h-8 bg-brand-primary rounded-full shadow-[0_0_15px_rgba(229,9,20,0.5)]"></div>
                    <h2 class="text-2xl font-black uppercase tracking-tight">More Like This</h2>
                </div>
                <div class="relative group/slider">
                    <!-- Horizontal Scroll Container -->
                    <div class="flex overflow-x-auto gap-4 sm:gap-5 pb-4 snap-x snap-mandatory scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach($related as $rel)
                            <div class="snap-start flex-shrink-0 w-[140px] sm:w-[160px] md:w-[180px] lg:w-[200px]">
                                @include('components.movie-card', ['movie' => $rel])
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Fading Edges -->
                    <div class="absolute top-0 right-0 bottom-4 w-24 bg-gradient-to-l from-bg-dark to-transparent pointer-events-none hidden md:block"></div>
                    <div class="absolute top-0 left-0 bottom-4 w-24 bg-gradient-to-r from-bg-dark to-transparent pointer-events-none hidden md:block"></div>
                </div>
            </div>
        @endif
    </div>

    @if($movie['video'])
    <script>
        lucide.createIcons();

        const video = document.getElementById('main-video');
        const playIcon = document.getElementById('play-icon');
        const volumeIcon = document.getElementById('volume-icon');
        const progressBar = document.getElementById('progress-bar');
        const progressFill = document.getElementById('progress-fill');
        const currentTimeEl = document.getElementById('current-time');
        const durationEl = document.getElementById('duration-time');
        const timeDisplay = document.getElementById('time-display');

        function formatTime(s) {
            const m = Math.floor(s / 60);
            const sec = Math.floor(s % 60).toString().padStart(2, '0');
            return `${m}:${sec}`;
        }

        function togglePlay() {
            if (video.paused) {
                video.play();
                playIcon.setAttribute('data-lucide', 'pause');
            } else {
                video.pause();
                playIcon.setAttribute('data-lucide', 'play');
            }
            lucide.createIcons();
        }

        function toggleMute() {
            video.muted = !video.muted;
            volumeIcon.setAttribute('data-lucide', video.muted ? 'volume-x' : 'volume-2');
            lucide.createIcons();
        }

        function skipTime(seconds) {
            video.currentTime = Math.max(0, Math.min(video.duration, video.currentTime + seconds));
        }

        function changeSpeed(speed) {
            video.playbackRate = parseFloat(speed);
        }

        function toggleFullscreen() {
            const wrapper = document.getElementById('video-wrapper');
            if (!document.fullscreenElement) {
                wrapper.requestFullscreen();
                document.getElementById('fullscreen-icon').setAttribute('data-lucide', 'minimize');
            } else {
                document.exitFullscreen();
                document.getElementById('fullscreen-icon').setAttribute('data-lucide', 'maximize');
            }
            lucide.createIcons();
        }

        function showRipple() {
            const ripple = document.getElementById('click-ripple');
            const icon = document.getElementById('ripple-icon');
            icon.setAttribute('data-lucide', video.paused ? 'pause' : 'play');
            lucide.createIcons();
            gsap.fromTo(ripple, { opacity: 1, scale: 0.8 }, { opacity: 0, scale: 1.5, duration: 0.6, ease: 'power2.out' });
        }

        // Progress bar update
        video.addEventListener('timeupdate', () => {
            if (!video.duration) return;
            const pct = (video.currentTime / video.duration) * 100;
            progressFill.style.width = pct + '%';
            progressBar.value = pct;
            currentTimeEl.textContent = formatTime(video.currentTime);
            timeDisplay.textContent = `${formatTime(video.currentTime)} / ${formatTime(video.duration)}`;
        });

        video.addEventListener('loadedmetadata', () => {
            durationEl.textContent = formatTime(video.duration);
        });

        progressBar.addEventListener('input', (e) => {
            video.currentTime = (e.target.value / 100) * video.duration;
            progressFill.style.width = e.target.value + '%';
        });

        document.getElementById('volume-bar').addEventListener('input', (e) => {
            video.volume = e.target.value;
            volumeIcon.setAttribute('data-lucide', e.target.value == 0 ? 'volume-x' : e.target.value < 0.5 ? 'volume-1' : 'volume-2');
            lucide.createIcons();
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (['INPUT', 'SELECT', 'TEXTAREA'].includes(e.target.tagName)) return;
            switch(e.key) {
                case ' ': case 'k': e.preventDefault(); togglePlay(); showRipple(); break;
                case 'ArrowLeft':  e.preventDefault(); skipTime(-10); break;
                case 'ArrowRight': e.preventDefault(); skipTime(10); break;
                case 'm': toggleMute(); break;
                case 'f': toggleFullscreen(); break;
            }
        });

        // Auto-hide controls
        let controlsTimeout;
        const wrapper = document.getElementById('video-wrapper');
        const controls = document.getElementById('video-controls');

        wrapper.addEventListener('mousemove', () => {
            controls.style.opacity = '1';
            clearTimeout(controlsTimeout);
            controlsTimeout = setTimeout(() => {
                if (!video.paused) controls.style.opacity = '0';
            }, 3000);
        });

        video.addEventListener('ended', () => {
            playIcon.setAttribute('data-lucide', 'play');
            lucide.createIcons();
            controls.style.opacity = '1';
        });
    </script>
    @else
    <script>
        lucide.createIcons();
    </script>
    @endif

</body>
</html>
