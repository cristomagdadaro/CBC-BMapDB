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

		<meta property="og:title" content="Plant Breeders and Innovators Network System">
		<meta property="og:description" content="A centralized and user-friendly database system that provides comprehensive access to crop biotechnology information in the Philippines. This platform enables researchers, policymakers, and the public to explore data on crop varieties, genetic modifications, research projects, and related approvals, fostering innovation and informed decision-making in agricultural biotechnology."
        />
		<meta property="og:image" content="{{ asset('img/pin-layout.jpg') }}">
		<meta property="og:url" content="{{ url()->current() }}">
		<meta property="og:type" content="website">
		<meta property="og:site_name" content="Plant Breeders and Innovators Network System">
		<meta property="og:image:width" content="1200">
		<meta property="og:image:height" content="630">
		<meta property="og:locale" content="en_PH">
		<meta property="og:image:alt" content="Plant Breeders and Innovators Network System Logo">
		<meta property="og:image:type" content="image/webp">
		<meta property="og:image:secure_url" content="{{ asset('img/pin-layout.jpg') }}">

        <meta name="google-site-verification" content="ZrD_iUGZg325WPHfCGqb7gySTuljzzaFlh1Zq3UdkJk" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-signin-client_id" content="605043117472-0nk2ffvrtcrgu7122k1jghtn9a7f4duo.apps.googleusercontent.com">

        <!-- Twitter Card for Social Sharing -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Plant Breeders and Innovators Network System" />
        <meta name="twitter:description" content="A centralized and user-friendly database system that provides comprehensive access to crop biotechnology information in the Philippines. This platform enables researchers, policymakers, and the public to explore data on crop varieties, genetic modifications, research projects, and related approvals, fostering innovation and informed decision-making in agricultural biotechnology." />
        <meta name="twitter:image" content="{{ asset('img/pin-layout.jpg') }}" />
        <meta name="twitter:site" content="@PhilRice" />

        <!-- JSON-LD Structured Data for Sitelinks and SEO -->
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "Plant Breeders and Innovators Network System",
          "url": "{{ url('/') }}",
          "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/') }}/search?q={search_term_string}",
            "query-input": "required name=search_term_string"
          },
          "image": "{{ asset('img/logos/pin.webp') }}",
          "description": "A centralized and user-friendly database system that provides comprehensive access to crop biotechnology information in the Philippines."
        }
        </script>

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
        <script src="https://js.pusher.com/beams/2.1.0/push-notifications-cdn.js"></script>
        <title>Plant Breeders and Innovators Network System | CBC PIN | Crop Biotechnology Center | PhilRice</title>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>

    <script>
        const beamsClient = new PusherPushNotifications.Client({
            instanceId: 'a2819254-58af-4d1f-a99b-72bfa4d2c0c1',
        });

        beamsClient.start()
            .then(() => beamsClient.addDeviceInterest('hello'))
            .then(() => console.log('Successfully registered and subscribed!'))
            .catch(console.error);
    </script>
</html>


{{-- Paste to CMD
Invoke-RestMethod `
-Uri "https://a2819254-58af-4d1f-a99b-72bfa4d2c0c1.pushnotifications.pusher.com/publish_api/v1/instances/a2819254-58af-4d1f-a99b-72bfa4d2c0c1/publishes" `
-Method POST `
-Headers @{
"Content-Type" = "application/json"
"Authorization" = "Bearer 35BE9D129473C9436642AFDF3CC60B309E11BFAA4ABF2DECA0840C72F4DD1D62"
} `
-Body '{"interests":["hello"],"web":{"notification":{"title":"Hello","body":"Hello, world!"}}}'
--}}
