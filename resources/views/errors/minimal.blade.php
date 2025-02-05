<!DOCTYPE html>
<html lang="en">
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
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <title>@yield('title')</title>

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
</head>
<body class="antialiased">
<div id="maintenance-particles-js" class="absolute top-0 left-0 w-full h-full -z-[999]"></div>
<div class="relative flex flex-col items-top justify-center min-h-screen sm:items-center sm:pt-0">
    <div class="flex flex-col items-center justify-center text-center">
        <div class="text-4xl font-bold text-gray-500 uppercase tracking-wider">
            @yield('title')
        </div>
        <div class="text-lg text-gray-500 tracking-wider">
            @yield('message')
        </div>
        <img src="/img/logos/pin.svg" class="w-[5rem] sm:w-[6rem] md:w-[7rem] lg:w-[8rem] h-auto" alt="PIN System Logo" />
    </div>
</div>
</body>
<script>
    function setupParticlesFor(id)  {
        particlesJS.load(id, '/particlesjs-config.json', function() {
            console.log('particles for Projects Page loaded');
        });
    }

    setupParticlesFor('maintenance-particles-js');
</script>
</html>
