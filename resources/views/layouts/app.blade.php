<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MOVIX - Modern Movie Landing Page')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />

    <!-- Tailwind CSS v4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <!-- Custom Tailwind Configuration -->
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
            
            --color-brand-primary: #e50914;
            --color-brand-secondary: #ff0000;
            --color-bg-dark: #080808;
            --color-bg-card: #141414;
        }

        @layer base {
            body {
                @apply bg-bg-dark text-white font-sans antialiased;
            }
        }
    </style>

    <!-- GSAP for animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
</head>

<body class="bg-bg-dark text-white antialiased">

    <!-- Include Navbar Component -->
    @include('components.navbar', ['searchQuery' => $searchQuery ?? ''])

    <!-- Main Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Include Footer Component -->
    @include('components.footer')

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Register ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);
    </script>
    
    @stack('scripts')
</body>

</html>
