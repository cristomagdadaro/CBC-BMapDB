<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SupportInfoController extends Controller
{
    public function whatIsPIN(): Response
    {
        return Inertia::render('Support/AboutUs');
    }

    public function termsOfUse(): Response
    {
        return Inertia::render('Support/TermsOfUse');
    }

    public function policyNotice(): Response
    {
        return Inertia::render('Support/PolicyNotice');
    }

    public function privacyPolicy(): Response
    {
        return Inertia::render('Support/PrivacyPolicy');
    }

    public function sitemap(): Response
    {
        //return Inertia::render('Support/Sitemap');
        $urls = [
            // Home Page
            [
                'name' => 'Home',
                'loc' => url('/'), // Home page
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            // Dashboard (accessible only for authenticated users)
            [
                'name' => 'Dashboard',
                'loc' => url('/dashboard'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            // Support Information Pages
            [
                'name' => 'About Us',
                'loc' => url('/support-info/about-us'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'name' => 'Terms of Use',
                'loc' => url('/support-info/terms-of-use'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'name' => 'Policy Notice',
                'loc' => url('/support-info/policy-notice'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'name' => 'Privacy Policy',
                'loc' => url('/support-info/privacy-policy'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'name' => 'Sitemap',
                'loc' => url('/support-info/sitemap'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'name' => 'Developers',
                'loc' => url('/support-info/developers'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            // Projects Pages
            [
                'name' => 'Projects Overview',
                'loc' => url('/projects'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ],
            [
                'name' => 'Biotech TWG Database',
                'loc' => url('/projects/twg-db'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'name' => 'Plant Breeders Map',
                'loc' => url('/projects/breedersmap-db'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
        ];

        // Dynamically add other routes if needed, like blog posts, etc.
        $dynamicRoutes = [
            // Example of dynamically adding routes
        ];

        // Merge static and dynamic URLs
        $urls = array_merge($urls, $dynamicRoutes);
        return Inertia::render('Support/Sitemap', [
            'urls' => $urls,
        ]);
    }

    public function developers(): Response
    {
        return Inertia::render('Support/Developers');
    }
}
