<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MOVIX - Register</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            --color-brand-primary: #e50914;
            --color-brand-secondary: #ff0000;
            --color-bg-dark: #080808;
        }
        @layer base {
            body { @apply bg-bg-dark text-white font-sans antialiased; }
        }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-bg-dark text-white antialiased">
    <div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
        <div class="relative z-10 w-full max-w-[420px]">
            <!-- Spotlight Card -->
            <div class="spotlight-card bg-neutral-900/40 backdrop-blur-3xl border border-neutral-800 rounded-[2.5rem] p-10 md:p-12 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.8)] relative overflow-hidden" data-spotlight-color="rgba(229, 9, 20, 0.15)">
                <div class="spotlight-gradient pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-500 rounded-[inherit]"></div>
                <div class="relative z-10">
                    <div class="text-center mb-10">
                        <div class="w-24 h-24 bg-gradient-to-tr from-brand-primary to-brand-secondary rounded-full mx-auto mb-8 flex items-center justify-center shadow-[0_0_30px_rgba(229,9,20,0.4)] relative">
                            <i data-lucide="user" class="w-12 h-12 text-white"></i>
                        </div>
                        <h2 class="text-3xl font-black tracking-[0.2em] mb-3 text-white">SIGN UP</h2>
                        <p class="text-neutral-500 text-sm font-medium tracking-wide">
                            Buat akun baru untuk menikmati fitur lengkap
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-500 text-xs font-medium">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-500 text-xs font-medium">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/register') }}" class="space-y-6">
                        @csrf
                        <!-- Email Field -->
                        <div class="space-y-2 group">
                            <label class="text-[10px] text-neutral-500 font-black uppercase tracking-[0.2em] ml-2 group-focus-within:text-brand-primary transition-colors">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-neutral-600 group-focus-within:text-brand-primary transition-colors">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                </div>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    class="w-full bg-neutral-950/50 border border-neutral-800 rounded-2xl pl-14 pr-6 py-5 focus:outline-none focus:ring-2 focus:ring-brand-primary/40 focus:border-brand-primary transition-all text-white placeholder:text-neutral-700 font-medium"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2 group">
                            <label class="text-[10px] text-neutral-500 font-black uppercase tracking-[0.2em] ml-2 group-focus-within:text-brand-primary transition-colors">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-neutral-600 group-focus-within:text-brand-primary transition-colors">
                                    <i data-lucide="lock" class="w-5 h-5"></i>
                                </div>
                                <input
                                    id="password-input"
                                    type="password"
                                    name="password"
                                    placeholder="••••••••"
                                    class="w-full bg-neutral-950/50 border border-neutral-800 rounded-2xl pl-14 pr-14 py-5 focus:outline-none focus:ring-2 focus:ring-brand-primary/40 focus:border-brand-primary transition-all text-white placeholder:text-neutral-700 font-medium"
                                    required
                                    minlength="4"
                                />
                                <button
                                    type="button"
                                    onclick="togglePassword()"
                                    class="absolute right-5 top-1/2 -translate-y-1/2 text-neutral-600 hover:text-white transition-colors"
                                >
                                    <i id="eye-icon" data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Register Button -->
                        <button
                            type="submit"
                            class="w-full bg-brand-primary hover:bg-brand-secondary text-white font-black py-6 rounded-2xl transition-all flex items-center justify-center gap-4 group shadow-2xl shadow-brand-primary/20 active:scale-[0.98] relative overflow-hidden mt-8"
                        >
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full"></div>
                            <span class="uppercase tracking-[0.3em] ml-2">CREATE ACCOUNT</span>
                            <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-2 transition-transform duration-300"></i>
                        </button>
                    </form>

                    <div class="mt-12 text-center space-y-4">
                        <div class="pt-4 border-t border-neutral-800">
                            <p class="text-neutral-500 text-xs font-medium mb-4">Sudah punya akun?</p>
                            <a href="{{ route('login') }}" class="text-white hover:text-brand-primary text-xs font-black uppercase tracking-widest transition-colors flex items-center justify-center gap-2">
                                Login Sekarang
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePassword() {
            const input = document.getElementById('password-input');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
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
