<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta
            name="keywords"
            content="DA-Crop Biotechnology Center,rice area, yield estimate, planting dates, SOS, flood and drought assessment, damage assessment, information technology, rice mapping, rice monitoring, remote sensing, crop modeling, field monitoring, ground truthing"
        />
        <meta name="author" content="Department of Agriculture - Crop Biotechnology Center (DA-CBC)" />
        <meta
            name="description"
            content="DA-Crop Biotechnology Center is the first rice monitoring system in Southeast Asia that uses satellite imagery and Information and Communication Technology (ICT). It generates information on planted rice area, yield, and rice areas at risk and affected by flood and drought. Through its data products, PRiSM supports the Department of Agriculture in its strategic planning, decision-making, development project implementation, and disaster preparedness."
        />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-signin-client_id" content="605043117472-0nk2ffvrtcrgu7122k1jghtn9a7f4duo.apps.googleusercontent.com">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://apis.google.com/js/platform.js"></script>
        {{-- Leaflet Interactive Map Library --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
                crossorigin=""></script>
        {{-- Leaflet Interactive Map Library --}}
        <!-- Scripts -->
        @routes()
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">

        @inertia
    </body>
</html>
