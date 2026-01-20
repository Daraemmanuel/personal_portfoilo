<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Inline script to detect system dark mode preference and apply it immediately --}}
    <script>
        (function() {
            const appearance = '{{ $appearance ?? 'system' }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    {{-- Inline style to set the HTML background color based on our theme in app.css --}}
    <style>
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }
    </style>

    <title inertia>{{ config('app.name', 'Dara Emmanuel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicons --}}
    <link rel="icon" href="/favicon.ico.png" sizes="any">
    <link rel="icon" href="/favicon.svg.png" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    <link rel="preconnect" href="https://fonts.bunny.net">
    {{-- Note: SRI (Subresource Integrity) requires generating hashes for each resource version --}}
    {{-- For Bunny Fonts, SRI is not practical as the CSS is dynamically generated --}}
    {{-- Using crossorigin="anonymous" ensures proper CORS handling --}}
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"
        crossorigin="anonymous" />

    @routes
    {{-- Single Vite entry; Inertia dynamically loads page components --}}
    @vite('resources/js/app.ts')
    @inertiaHead

    @if (config('services.google_analytics.id'))
        <!-- Google Analytics -->
        {{-- Note: SRI is not available for dynamic gtag.js as the hash changes with each version --}}
        {{-- For production, consider using a CSP nonce or loading GA through a tag manager --}}
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics.id') }}"
            crossorigin="anonymous"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ config('services.google_analytics.id') }}');
        </script>
    @endif
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
