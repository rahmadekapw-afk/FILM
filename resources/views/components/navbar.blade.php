@props(['user' => null, 'searchQuery' => ''])

<nav id="navbar" class="fixed w-full z-50 transition-all duration-300 bg-transparent py-5">
    <div class="container mx-auto px-4 md:px-6 flex items-center justify-between">
        <div class="flex items-center gap-8">
            <a href="{{ auth()->check() ? url('/home') : url('/') }}" class="flex items-center gap-2 group">
                <div class="bg-brand-primary p-1 rounded-md group-hover:bg-brand-secondary transition-colors">
                    <i data-lucide="play" class="w-6 h-6 fill-white text-white"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter text-white">MOVIX</span>
            </a>

            <div class="hidden md:flex items-center gap-6">
                @foreach(['Movies', 'TV Shows', 'New & Popular', 'My List'] as $item)
                    <a href="#{{ Str::slug($item) }}" class="text-sm font-medium text-white/70 hover:text-white transition-colors">{{ $item }}</a>
                @endforeach
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="relative hidden lg:block">
                <form action="{{ url('/') }}" method="GET">
                    <input
                        type="text"
                        name="q"
                        value="{{ $searchQuery }}"
                        placeholder="Search titles..."
                        class="bg-white/5 border border-white/10 rounded-full py-1.5 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 w-64 transition-all text-white"
                        autocomplete="off"
                    />
                    <i data-lucide="search" class="w-4 h-4 text-white/50 absolute left-4 top-1/2 -translate-y-1/2"></i>
                </form>
            </div>

            <button class="p-2 text-white/70 hover:text-white lg:hidden" id="mobile-search-btn">
                <i data-lucide="search" class="w-5 h-5"></i>
            </button>

            @auth
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-1.5 rounded-full text-sm font-semibold text-white/80">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span>{{ auth()->user()->name }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="hidden sm:inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-4 py-1.5 rounded-full text-sm font-semibold transition-all border border-white/10">
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="hidden sm:flex items-center gap-2 bg-brand-primary hover:bg-brand-secondary text-white px-4 py-1.5 rounded-full text-sm font-semibold transition-all">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span>Sign In</span>
                </a>
            @endauth

            <button id="mobile-menu-btn" class="p-2 text-white md:hidden">
                <i data-lucide="menu"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-bg-dark border-b border-white/10 px-4 py-6 space-y-4">
        @foreach(['Movies', 'TV Shows', 'New & Popular', 'My List'] as $item)
            <a href="#{{ Str::slug($item) }}" class="block text-lg font-medium text-white/70 hover:text-white">{{ $item }}</a>
        @endforeach

        @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="block text-center w-full bg-white/10 hover:bg-white/20 text-white py-3 rounded-xl font-bold transition-all border border-white/10">
                    Log Out ({{ auth()->user()->name }})
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block text-center w-full bg-brand-primary hover:bg-brand-secondary text-white py-3 rounded-xl font-bold transition-all">
                Sign In
            </a>
        @endauth
    </div>
</nav>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('bg-bg-dark/95', 'backdrop-blur-md', 'border-b', 'border-white/10', 'py-3');
                navbar.classList.remove('bg-transparent', 'py-5');
            } else {
                navbar.classList.add('bg-transparent', 'py-5');
                navbar.classList.remove('bg-bg-dark/95', 'backdrop-blur-md', 'border-b', 'border-white/10', 'py-3');
            }
        });

        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const iconMenu = menuBtn.querySelector('i');
        let isMenuOpen = false;

        menuBtn.addEventListener('click', () => {
            isMenuOpen = !isMenuOpen;
            if (isMenuOpen) {
                mobileMenu.classList.remove('hidden');
                iconMenu.setAttribute('data-lucide', 'x');
                lucide.createIcons();
            } else {
                mobileMenu.classList.add('hidden');
                iconMenu.setAttribute('data-lucide', 'menu');
                lucide.createIcons();
            }
        });
    });
</script>
@endpush
