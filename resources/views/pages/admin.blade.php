<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MOVIX - Admin Panel</title>
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
        @layer base {
            body { @apply bg-bg-dark text-white font-sans antialiased; }
        }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-bg-dark text-white antialiased">
    <div class="min-h-screen selection:bg-brand-primary/30 overflow-x-hidden pb-32">
        <div class="relative z-10 max-w-5xl mx-auto px-6 py-12">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-neutral-400 hover:text-white transition-colors mb-8 group">
                <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                Back to Movies
            </a>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-2">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-brand-primary/20 rounded-2xl border border-brand-primary/30">
                        <i data-lucide="film" class="w-8 h-8 text-brand-primary"></i>
                    </div>
                    <div>
                        <h1 id="form-title" class="text-4xl font-bold tracking-tight block">Add New Movie</h1>
                        <p id="form-subtitle" class="text-neutral-400 mt-1">Populate your movie library with new titles</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button id="cancel-edit-btn" onclick="cancelEdit()" class="hidden items-center gap-2 px-6 py-3 bg-neutral-800 hover:bg-neutral-700 rounded-2xl transition-all border border-neutral-700 text-sm font-bold uppercase tracking-widest text-neutral-300">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Cancel Edit
                    </button>

                    <form id="import-form" action="{{ route('movies.import') }}" method="POST" enctype="multipart/form-data" class="inline">
                        @csrf
                        <input type="file" id="import-file" name="file" accept=".xlsx,.xls,.csv" class="hidden" onchange="this.form.submit()" />
                        <button type="button" onclick="document.getElementById('import-file').click()" class="flex items-center gap-2 px-6 py-3 bg-emerald-600/10 hover:bg-emerald-600/20 rounded-2xl transition-all border border-emerald-600/30 text-sm font-bold uppercase tracking-widest text-emerald-400">
                            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                            Import Excel
                        </button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="mt-6 flex items-center gap-4 p-5 rounded-[2rem] border bg-emerald-500/10 border-emerald-500/30 text-emerald-400">
                    <i data-lucide="check-circle-2" class="w-6 h-6 flex-shrink-0"></i>
                    <span class="text-sm font-black uppercase tracking-widest">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mt-6 flex items-center gap-4 p-5 rounded-[2rem] border bg-red-500/10 border-red-500/30 text-red-400">
                    <i data-lucide="alert-circle" class="w-6 h-6 flex-shrink-0"></i>
                    <span class="text-sm font-black uppercase tracking-widest">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mt-6 flex items-center gap-4 p-5 rounded-[2rem] border bg-red-500/10 border-red-500/30 text-red-400">
                    <i data-lucide="alert-circle" class="w-6 h-6 flex-shrink-0"></i>
                    <span class="text-sm font-black uppercase tracking-widest">{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Add/Edit Form -->
            <div class="mt-12">
                <div class="spotlight-card bg-neutral-900/40 backdrop-blur-3xl border border-neutral-800 rounded-[2.5rem] p-8 md:p-12 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.5)] relative overflow-hidden" data-spotlight-color="rgba(229, 9, 20, 0.1)">
                    <div class="spotlight-gradient pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-500 rounded-[inherit]"></div>
                    <div class="relative z-10">
                        <form id="movie-form" action="{{ route('movies.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-12">
                            @csrf
                            <input type="hidden" id="form-method" name="_method" value="POST" disabled />

                            <!-- Left Column: Details -->
                            <div class="space-y-8">
                                <div class="group">
                                    <label class="block text-sm font-medium text-neutral-400 mb-3 group-focus-within:text-brand-primary transition-colors uppercase tracking-widest">
                                        Movie Title
                                    </label>
                                    <input
                                        type="text"
                                        id="input-title"
                                        name="title"
                                        placeholder="e.g. Inception (2010)"
                                        class="w-full bg-neutral-950/50 border border-neutral-800 rounded-2xl px-6 py-5 focus:outline-none focus:ring-2 focus:ring-brand-primary/40 focus:border-brand-primary transition-all text-white placeholder:text-neutral-600 backdrop-blur-sm text-lg"
                                        required
                                    />
                                </div>

                                <div class="group">
                                    <label class="block text-sm font-medium text-neutral-400 mb-3 group-focus-within:text-brand-primary transition-colors uppercase tracking-widest">
                                        Genres
                                    </label>
                                    <input
                                        type="text"
                                        id="input-genres"
                                        name="genres"
                                        placeholder="e.g. Action|Sci-Fi|Thriller"
                                        class="w-full bg-neutral-950/50 border border-neutral-800 rounded-2xl px-6 py-5 focus:outline-none focus:ring-2 focus:ring-brand-primary/40 focus:border-brand-primary transition-all text-white placeholder:text-neutral-600 backdrop-blur-sm text-lg"
                                        required
                                    />
                                    <p class="text-[10px] uppercase tracking-widest text-neutral-500 mt-3 font-black px-1 opacity-70">
                                        Use pipe <span class="text-brand-primary px-1">|</span> to separate multiple genres
                                    </p>
                                </div>

                                <button
                                    type="submit"
                                    class="w-full bg-brand-primary hover:bg-brand-secondary text-white font-black py-6 rounded-2xl transition-all flex items-center justify-center gap-4 group shadow-2xl shadow-brand-primary/20 active:scale-[0.98] relative overflow-hidden text-lg"
                                >
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full"></div>
                                    <i id="submit-icon" data-lucide="plus-circle" class="w-6 h-6 group-hover:rotate-90 transition-transform duration-500"></i>
                                    <span id="submit-text" class="uppercase tracking-[0.2em]">Add Movie</span>
                                </button>
                            </div>

                            <!-- Right Column: Poster + Video Upload -->
                            <div class="space-y-6">
                                <label class="block text-sm font-medium text-neutral-400 mb-3 uppercase tracking-widest text-center md:text-left">
                                    Movie Poster
                                </label>
                                <div class="relative aspect-[2/3] w-full max-w-[220px] mx-auto md:mx-0 rounded-[2rem] border-2 border-dashed transition-all duration-500 flex flex-col items-center justify-center overflow-hidden group shadow-inner border-neutral-800 hover:border-brand-primary/50 bg-neutral-950/50">
                                    <img id="poster-preview" src="" alt="Preview" class="hidden w-full h-full object-cover absolute inset-0 transition-transform group-hover:scale-110 duration-1000" />
                                    <div id="poster-placeholder" class="text-center p-6">
                                        <div class="w-16 h-16 bg-neutral-900 rounded-[2rem] flex items-center justify-center mx-auto mb-4 border border-neutral-800 group-hover:bg-brand-primary/10 group-hover:border-brand-primary/30 transition-all duration-700">
                                            <i data-lucide="camera" class="w-8 h-8 text-neutral-500 group-hover:text-brand-primary transition-colors duration-500"></i>
                                        </div>
                                        <p class="text-neutral-400 font-black text-sm mb-1 uppercase tracking-widest">Upload Poster</p>
                                        <p class="text-neutral-600 text-[10px] font-bold tracking-widest uppercase opacity-70">JPG, PNG, WEBP</p>
                                    </div>
                                    <div id="poster-overlay" class="hidden absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-all duration-500 flex-col items-center justify-center gap-2 backdrop-blur-md">
                                        <i data-lucide="camera" class="w-6 h-6 text-white"></i>
                                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white">Change</span>
                                    </div>
                                    <input type="file" name="poster" accept="image/*" onchange="previewPoster(event)" class="absolute inset-0 opacity-0 cursor-pointer" />
                                </div>

                                <!-- Video Upload -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-neutral-400 uppercase tracking-widest">
                                        Video File
                                    </label>
                                    <div id="video-drop-zone" class="relative w-full rounded-2xl border-2 border-dashed border-neutral-800 hover:border-brand-primary/50 bg-neutral-950/50 p-6 flex flex-col items-center gap-3 transition-all cursor-pointer group">
                                        <div class="w-12 h-12 bg-neutral-900 rounded-2xl flex items-center justify-center border border-neutral-800 group-hover:bg-brand-primary/10 group-hover:border-brand-primary/30 transition-all">
                                            <i data-lucide="file-video" class="w-6 h-6 text-neutral-500 group-hover:text-brand-primary transition-colors"></i>
                                        </div>
                                        <div class="text-center">
                                            <p id="video-label" class="text-neutral-400 font-black text-xs uppercase tracking-widest">Upload Video</p>
                                            <p class="text-neutral-600 text-[10px] mt-1">MP4, WEBM, OGG — Max 500MB</p>
                                        </div>
                                        <input type="file" name="video" id="video-input" accept="video/mp4,video/webm,video/ogg,.avi,.mov" onchange="previewVideo(event)" class="absolute inset-0 opacity-0 cursor-pointer" />
                                    </div>
                                    <div id="video-progress" class="hidden space-y-1">
                                        <div class="flex justify-between text-[10px] text-neutral-500 font-bold uppercase tracking-widest">
                                            <span id="video-filename-label" class="truncate">uploading...</span>
                                            <span id="video-size-label"></span>
                                        </div>
                                        <div class="h-1.5 bg-neutral-800 rounded-full overflow-hidden">
                                            <div class="h-full bg-brand-primary rounded-full animate-pulse" style="width: 100%"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description & Duration -->
                                <div class="space-y-4">
                                    <div class="group">
                                        <label class="block text-xs font-black uppercase tracking-widest text-neutral-500 mb-2 group-focus-within:text-brand-primary transition-colors">Duration</label>
                                        <input type="text" id="input-duration" name="duration" placeholder="e.g. 2h 28m" class="w-full bg-neutral-950/50 border border-neutral-800 rounded-xl px-4 py-3 text-sm text-white placeholder:text-neutral-600 focus:outline-none focus:ring-2 focus:ring-brand-primary/40 focus:border-brand-primary transition-all" />
                                    </div>
                                    <div class="group">
                                        <label class="block text-xs font-black uppercase tracking-widest text-neutral-500 mb-2 group-focus-within:text-brand-primary transition-colors">Description</label>
                                        <textarea id="input-description" name="description" rows="4" placeholder="Short synopsis of the movie..." class="w-full bg-neutral-950/50 border border-neutral-800 rounded-xl px-4 py-3 text-sm text-white placeholder:text-neutral-600 focus:outline-none focus:ring-2 focus:ring-brand-primary/40 focus:border-brand-primary transition-all resize-none"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Movie List Section -->
            <div class="mt-32">
                <div class="flex items-center justify-between mb-12">
                    <h2 class="text-3xl font-black flex items-center gap-4 uppercase tracking-[0.1em]">
                        <div class="w-1.5 h-10 bg-brand-primary rounded-full shadow-[0_0_15px_rgba(229,9,20,0.5)]"></div>
                        Dashboard
                    </h2>
                    <div class="px-4 py-2 rounded-xl bg-neutral-900/50 border border-neutral-800 text-neutral-500 text-xs font-black uppercase tracking-widest">
                        Total: <span class="text-white ml-1">{{ count($movies) }}</span>
                    </div>
                </div>

                @if(count($movies) === 0)
                    <div class="text-center py-32 rounded-[3rem] border-2 border-dashed border-neutral-800 bg-neutral-900/10">
                        <i data-lucide="film" class="w-16 h-16 mx-auto mb-6 text-neutral-800"></i>
                        <p class="text-neutral-500 uppercase font-black tracking-widest">Your library is empty</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($movies as $movie)
                            <div class="group relative bg-neutral-900/20 backdrop-blur-sm border border-neutral-800/50 rounded-[2rem] p-5 flex gap-6 hover:bg-neutral-900/40 transition-all duration-500 hover:border-brand-primary/30 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.4)]">
                                <div class="w-40 aspect-video rounded-2xl overflow-hidden flex-shrink-0 bg-neutral-800 border border-neutral-700/50 shadow-lg transition-transform duration-500 group-hover:scale-[1.02]">
                                    @if($movie['image'])
                                        <img src="{{ $movie['image'] }}" alt="{{ $movie['title'] }}" class="w-full h-full object-cover" />
                                    @else
                                        <div class="w-full h-full flex items-center justify-center italic text-[10px] text-neutral-600 text-center px-1 font-bold">MISSING_POSTER</div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col justify-center pr-24">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-black text-xl truncate group-hover:text-brand-primary transition-colors duration-500 uppercase tracking-tight">{{ $movie['cleanTitle'] ?? $movie['title'] }}</h3>
                                        @if($movie['video'])
                                            <span class="flex-shrink-0 text-[9px] font-black uppercase tracking-widest text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 px-2 py-0.5 rounded-full">VIDEO</span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($movie['genres'] as $genre)
                                            <span class="text-[10px] font-black uppercase tracking-widest text-neutral-500 bg-neutral-800/50 px-2 py-1 rounded-md border border-neutral-700/30">{{ $genre }}</span>
                                        @endforeach
                                    </div>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <span class="px-3 py-1 bg-neutral-950/50 rounded-lg text-[10px] text-neutral-500 border border-neutral-800 font-black">#{{ $movie['id'] }}</span>
                                        <span class="px-3 py-1 bg-brand-primary/5 rounded-lg text-[10px] text-brand-primary border border-brand-primary/20 font-black">★ {{ $movie['rating'] ?: '0.0' }}</span>
                                        @if($movie['duration'] && $movie['duration'] !== 'N/A')
                                            <span class="px-3 py-1 bg-white/5 rounded-lg text-[10px] text-neutral-500 border border-neutral-800 font-black">{{ $movie['duration'] }}</span>
                                        @endif
                                        <a href="{{ route('watch', $movie['id']) }}" class="px-3 py-1 bg-white/5 hover:bg-brand-primary/10 rounded-lg text-[10px] text-white/50 hover:text-brand-primary border border-white/10 hover:border-brand-primary/30 font-black transition-all flex items-center gap-1">
                                            <i data-lucide="play" class="w-2.5 h-2.5"></i> Watch
                                        </a>
                                    </div>
                                </div>
                                <div class="absolute top-5 right-5 flex flex-col gap-3">
                                    <button
                                        onclick="editMovie({{ $movie['id'] }}, '{{ addslashes($movie['title']) }}', '{{ implode('|', $movie['genres']) }}', '{{ $movie['image'] }}', '{{ addslashes($movie['description'] ?? '') }}', '{{ addslashes($movie['duration'] ?? '') }}')"
                                        class="p-3 bg-neutral-950/80 hover:bg-brand-primary rounded-2xl transition-all duration-300 border border-neutral-800 hover:border-brand-primary group/btn shadow-xl active:scale-95"
                                        title="Edit"
                                    >
                                        <i data-lucide="edit-3" class="w-5 h-5 text-neutral-400 group-hover/btn:text-white transition-colors"></i>
                                    </button>
                                    <button
                                        type="button"
                                        onclick="confirmDelete({{ $movie['id'] }}, '{{ addslashes($movie['title']) }}')"
                                        class="p-3 bg-neutral-950/80 hover:bg-red-600 rounded-2xl transition-all duration-300 border border-neutral-800 hover:border-red-600 group/btn shadow-xl active:scale-95"
                                        title="Delete"
                                    >
                                        <i data-lucide="trash-2" class="w-5 h-5 text-neutral-400 group-hover/btn:text-white transition-colors"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-24 text-center">
                <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full bg-neutral-900 border border-neutral-800 text-neutral-500 text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                    Administrator Level Access • v2.0 CRUD
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="bg-neutral-900 border border-neutral-800 rounded-[2rem] p-8 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex items-center gap-4 mb-6">
                <div class="p-3 bg-red-500/20 rounded-2xl border border-red-500/30">
                    <i data-lucide="trash-2" class="w-6 h-6 text-red-400"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-white uppercase tracking-tight">Delete Movie</h3>
                    <p id="delete-modal-subtitle" class="text-neutral-500 text-sm mt-0.5">This action cannot be undone.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button
                    onclick="closeDeleteModal()"
                    class="flex-1 py-4 bg-neutral-800 hover:bg-neutral-700 text-white font-bold rounded-2xl transition-all uppercase tracking-widest text-sm"
                >
                    Cancel
                </button>
                <form id="delete-form" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full py-4 bg-red-600 hover:bg-red-500 text-white font-black rounded-2xl transition-all uppercase tracking-widest text-sm"
                    >
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function confirmDelete(id, title) {
            const modal = document.getElementById('delete-modal');
            const form = document.getElementById('delete-form');
            const subtitle = document.getElementById('delete-modal-subtitle');
            form.action = '/admin/movies/' + id;
            subtitle.textContent = 'Delete "' + title + '"? This cannot be undone.';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        function previewPoster(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('poster-preview');
                const placeholder = document.getElementById('poster-placeholder');
                const overlay = document.getElementById('poster-overlay');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            };
            reader.readAsDataURL(file);
        }

        function previewVideo(event) {
            const file = event.target.files[0];
            if (!file) return;
            const label = document.getElementById('video-label');
            const progress = document.getElementById('video-progress');
            const nameLabel = document.getElementById('video-filename-label');
            const sizeLabel = document.getElementById('video-size-label');
            const sizeMB = (file.size / (1024 * 1024)).toFixed(1);
            label.textContent = file.name;
            nameLabel.textContent = file.name;
            sizeLabel.textContent = sizeMB + ' MB';
            progress.classList.remove('hidden');
            document.getElementById('video-drop-zone').classList.add('border-brand-primary/50', 'bg-brand-primary/5');
        }

        function editMovie(id, title, genres, image, description, duration) {
            const form = document.getElementById('movie-form');
            form.action = '/admin/movies/' + id;

            document.getElementById('input-title').value = title;
            document.getElementById('input-genres').value = genres;
            document.getElementById('input-description').value = description || '';
            document.getElementById('input-duration').value = duration || '';
            document.getElementById('form-title').textContent = 'Edit Movie';
            document.getElementById('form-subtitle').textContent = 'Updating: ' + title;
            document.getElementById('submit-text').textContent = 'Update Movie';
            document.getElementById('submit-icon').setAttribute('data-lucide', 'save');
            document.getElementById('cancel-edit-btn').classList.remove('hidden');
            document.getElementById('cancel-edit-btn').classList.add('flex');

            if (image) {
                const preview = document.getElementById('poster-preview');
                const placeholder = document.getElementById('poster-placeholder');
                const overlay = document.getElementById('poster-overlay');
                preview.src = image;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            }

            lucide.createIcons();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function cancelEdit() {
            const form = document.getElementById('movie-form');
            form.action = '{{ route("movies.store") }}';

            document.getElementById('input-title').value = '';
            document.getElementById('input-genres').value = '';
            document.getElementById('input-description').value = '';
            document.getElementById('input-duration').value = '';
            document.getElementById('video-label').textContent = 'Upload Video';
            document.getElementById('video-progress').classList.add('hidden');
            document.getElementById('video-drop-zone').classList.remove('border-brand-primary/50', 'bg-brand-primary/5');
            document.getElementById('form-title').textContent = 'Add New Movie';
            document.getElementById('form-subtitle').textContent = 'Populate your movie library with new titles';
            document.getElementById('submit-text').textContent = 'Add Movie';
            document.getElementById('submit-icon').setAttribute('data-lucide', 'plus-circle');
            document.getElementById('cancel-edit-btn').classList.add('hidden');
            document.getElementById('cancel-edit-btn').classList.remove('flex');

            const preview = document.getElementById('poster-preview');
            const placeholder = document.getElementById('poster-placeholder');
            const overlay = document.getElementById('poster-overlay');
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');

            lucide.createIcons();
        }

        // Spotlight effect
        document.querySelectorAll('.spotlight-card').forEach(card => {
            const gradient = card.querySelector('.spotlight-gradient');
            const color = card.dataset.spotlightColor;
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                gradient.style.background = `radial-gradient(600px circle at ${e.clientX - rect.left}px ${e.clientY - rect.top}px, ${color}, transparent 40%)`;
                gradient.style.opacity = '1';
            });
            card.addEventListener('mouseleave', () => { gradient.style.opacity = '0'; });
        });
    </script>
</body>
</html>
