<?php

namespace App\Http\Controllers;

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

    /*public function policyNotice(): Response
    {
        return Inertia::render('Support/PolicyNotice');
    }*/

    public function privacyPolicy(): Response
    {
        return Inertia::render('Support/PrivacyPolicy');
    }

    public function dataPrivacy(): Response
    {
        return Inertia::render('Support/DataPrivacy');
    }

    public function sitemap(): Response
    {
        $urls = $this->buildSitemapUrls();
        return Inertia::render('Support/Sitemap', [
            'urls' => $urls,
        ]);
    }

    public function sitemapXml(): \Illuminate\Http\Response
    {
        $urls = $this->buildSitemapUrls();

        $body = collect($urls)->map(function ($url) {
            return "  <url>\n".
                "    <loc>{$url['loc']}</loc>\n".
                "    <lastmod>{$url['lastmod']}</lastmod>\n".
                "    <changefreq>{$url['changefreq']}</changefreq>\n".
                "    <priority>{$url['priority']}</priority>\n".
                "  </url>";
        })->implode("\n");

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
            "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n".
            $body."\n".
            "</urlset>";

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    private function buildSitemapUrls(): array
    {
        $urls = [
            // Home Page
            [
                'name' => 'Welcome',
                'loc' => url('/'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
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
                'name' => 'Data Privacy Notice',
                'loc' => url('/support-info/data-privacy'),
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
                'name' => 'Contributors',
                'loc' => url('/support-info/contributors'),
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

        $dynamicRoutes = [
            // Example of dynamically adding routes
        ];

        return array_merge($urls, $dynamicRoutes);
    }

    public function contributors(): Response
    {
        return Inertia::render('Support/Contributors');
    }
}
