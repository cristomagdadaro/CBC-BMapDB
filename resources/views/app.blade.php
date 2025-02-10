<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta
            name="keywords"
            content="DA-Crop Biotechnology Center, DA-CBC, PIN, CBC PIN, Plant Breeders and Innovators Network, Philippine Rice Research Institute, PhilRice, DA-BPO, Biotechnology Program Office, Biotech Program Office, Crop Biotechnology, Plant Breeders, Breeder, Plant Breeders Map, Biotech TWG, Biotech"
        />
        <meta name="author" content="Department of Agriculture - Crop Biotechnology Center" />
        <meta
            name="description"
            content="A centralized and user-friendly database system that provides comprehensive access to crop biotechnology information in the Philippines. This platform enables researchers, policymakers, and the public to explore data on crop varieties, genetic modifications, research projects, and related approvals, fostering innovation and informed decision-making in agricultural biotechnology."
        />

        <meta name="google-site-verification" content="ZrD_iUGZg325WPHfCGqb7gySTuljzzaFlh1Zq3UdkJk" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-signin-client_id" content="605043117472-0nk2ffvrtcrgu7122k1jghtn9a7f4duo.apps.googleusercontent.com">
        <title inertia>{{ config('app.name', 'PIN System') }} - Department of Agriculture Crop Biotechnology Center</title>
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        {{-- Leaflet Interactive Map Library --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
                crossorigin=""></script>
        {{-- Leaflet Interactive Map Library --}}
        <!-- Scripts -->

        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
        <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
        <script>
            window.AppConfig = {
                applications: @json(config('system_variables.applications')),
            };
        </script>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
