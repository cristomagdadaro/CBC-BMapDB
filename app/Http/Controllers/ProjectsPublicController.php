<?php

namespace App\Http\Controllers;

use Detection\MobileDetect;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Modules\PbMap\Models\Breeder;

class ProjectsPublicController extends Controller
{
    public function home(Request $request): Response
    {
        $data = Breeder::join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation')
            ->selectRaw('loc_cities.provDesc as label, COUNT(*) as total')
            ->groupBy('loc_cities.provDesc')
            ->orderByDesc('total')
            ->get();

        $formattedData = $data->map(function ($item) {
            return [
                'id' => Str::slug($item->label),
                'key' => Str::slug($item->label),
                'province' => $item->label,
                'data' => $item->total,
            ];
        });

        return Inertia::render('Projects', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'breedersmap_overview' => $formattedData,
            'heroBackgroundImages' => $this->resolveHeroCarouselImages($request),
        ]);
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Projects', [
            'heroBackgroundImages' => $this->resolveHeroCarouselImages($request),
        ]);
    }

    private function resolveHeroCarouselImages(Request $request): array
    {
        $detect = new MobileDetect();
        $detect->setUserAgent($request->userAgent() ?? '');

        $deviceType = $detect->isTablet()
            ? 'tablet'
            : ($detect->isMobile() ? 'mobile' : 'desktop');

        $variantPriority = match ($deviceType) {
            'mobile' => ['mobile', 'tablet', 'desktop', 'laptop'],
            'tablet' => ['tablet', 'desktop', 'laptop', 'mobile'],
            default => ['desktop', 'laptop', 'tablet', 'mobile'],
        };

        $resolvedImages = [];
        $extensions = ['jpg', 'webp', 'png', 'jpeg'];

        for ($index = 1; $index <= 4; $index++) {
            $resolvedPath = null;

            foreach ($variantPriority as $variant) {
                foreach ($extensions as $extension) {
                    $candidate = "img/carousel/{$variant}/image-{$index}.{$extension}";
                    if (File::exists(public_path($candidate))) {
                        $resolvedPath = '/' . $candidate;
                        break 2;
                    }
                }
            }

            if (!$resolvedPath) {
                foreach ($extensions as $extension) {
                    $fallback = "img/carousel/image-{$index}.{$extension}";
                    if (File::exists(public_path($fallback))) {
                        $resolvedPath = '/' . $fallback;
                        break;
                    }
                }
            }

            if ($resolvedPath) {
                $resolvedImages[] = $resolvedPath;
            }
        }

        return $resolvedImages;
    }
}
