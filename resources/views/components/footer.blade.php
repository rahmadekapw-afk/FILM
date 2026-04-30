<footer class="bg-bg-card border-t border-white/5 pt-20 pb-10 mt-20">
    <div class="container mx-auto px-4 md:px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <div class="space-y-6">
                <a href="/" class="flex items-center gap-2">
                    <div class="bg-brand-primary p-1 rounded-md">
                        <i data-lucide="play" class="w-5 h-5 fill-white text-white"></i>
                    </div>
                    <span class="text-xl font-black tracking-tighter text-white">MOVIX</span>
                </a>
                <p class="text-white/50 text-sm leading-relaxed max-w-xs">
                    The ultimate destination for movie lovers. Stream your favorite movies and TV shows in 4K Ultra HD anywhere, anytime.
                </p>
                <div class="flex items-center gap-4">
                    @foreach(['twitter', 'instagram', 'github', 'mail'] as $icon)
                        <a href="#" class="p-2.5 bg-white/5 rounded-xl text-white/50 hover:text-brand-primary hover:bg-white/10 transition-all">
                            <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6">Explore</h4>
                <ul class="space-y-4">
                    @foreach(['Action Movies', 'Comedy Movies', 'Horror Movies', 'Animation', 'TV Series'] as $link)
                        <li><a href="#" class="text-white/50 hover:text-white text-sm transition-colors">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6">Support</h4>
                <ul class="space-y-4">
                    @foreach(['FAQ', 'Help Center', 'Terms of Use', 'Privacy Policy', 'Cookie Preferences'] as $link)
                        <li><a href="#" class="text-white/50 hover:text-white text-sm transition-colors">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="space-y-6">
                <h4 class="text-white font-bold">Newsletter</h4>
                <p class="text-white/50 text-sm">Subscribe to get the latest updates and movie news.</p>
                <div class="relative">
                    <input
                        type="email"
                        placeholder="Your email address"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-6 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 text-white"
                    />
                    <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-brand-primary p-2 rounded-xl hover:bg-brand-secondary transition-colors">
                        <i data-lucide="play" class="w-4 h-4 fill-white text-white"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="border-t border-white/5 pt-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-white/30 text-xs font-medium">
                © 2026 Wrdhni Entertainment. All rights reserved.
            </p>
            <div class="flex items-center gap-8">
                <a href="#" class="text-white/30 hover:text-white text-xs transition-colors">Terms of Service</a>
                <a href="#" class="text-white/30 hover:text-white text-xs transition-colors">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
