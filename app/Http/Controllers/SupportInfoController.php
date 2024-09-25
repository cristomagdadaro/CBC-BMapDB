<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SupportInfoController extends Controller
{
    public function aboutUs(): Response
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
        return Inertia::render('Support/Sitemap');
    }

    public function developers(): Response
    {
        return Inertia::render('Support/Developers');
    }
}
