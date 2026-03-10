<?php

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\Location\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutes = config('list_of_identified_biotech_intitutes');

        foreach ($institutes as $institute) {
            $city = $this->resolveCity($institute);

            if (!$city) {
                if ($this->command) {
                    $this->command->warn(sprintf(
                        'Institute city not matched: %s | city=%s | province=%s | region=%s',
                        (string) ($institute['name'] ?? 'Unknown'),
                        (string) ($institute['city'] ?? ''),
                        (string) ($institute['province'] ?? ''),
                        (string) ($institute['region'] ?? '')
                    ));
                }
                $city = City::query()->first();
            }

            Institute::firstOrCreate([
                'name' => $institute['name'],
            ], [
                'inst_type' => $institute['inst_type'],
                'geolocation' => $city?->id,
                'website' => $institute['website'],
                'email' => $institute['email'],
                'phone' => $institute['phone'],
            ]);
        }
    }

    private function resolveCity(array $institute): ?City
    {
        $cityName = trim((string) ($institute['city'] ?? ''));
        $province = $this->normalizeProvince((string) ($institute['province'] ?? ''));
        $region = $this->normalizeRegion((string) ($institute['region'] ?? ''));

        if ($cityName === '' || $province === '' || $region === '') {
            return null;
        }

        $candidates = $this->buildCityCandidates($cityName);

        foreach ($candidates as $candidate) {
            $query = City::query()->where('cityDesc', $candidate);

            $query->where('provDesc', $province)
                ->where('regDesc', $region);

            $city = $query->first();
            if ($city) {
                return $city;
            }
        }
        
        return null;
    }

    private function normalizeRegion(string $region): string
    {
        $normalized = strtoupper(trim($region));

        $map = [
            'REGION XIV' => 'CAR',
        ];

        return $map[$normalized] ?? $normalized;
    }

    private function normalizeProvince(string $province): string
    {
        $normalized = trim($province);
        $key = strtolower($normalized);

        $map = [
            'compostela valley' => 'Davao de Oro',
        ];

        return $map[$key] ?? $normalized;
    }

    private function buildCityCandidates(string $cityName): array
    {
        $candidates = [$cityName];

        $normalized = preg_replace('/\s+/', ' ', trim($cityName));
        $normalized = preg_replace('/^(City of|Science City of|Municipality of)\s+/i', '', $normalized);
        $normalized = preg_replace('/\s+City$/i', '', $normalized);
        $normalized = preg_replace('/^City of\s+/i', '', $normalized);

        if ($normalized !== '' && $normalized !== $cityName) {
            $candidates[] = $normalized;
        }

        $variants = [
            'San Fernando' => ['San Fernando City'],
            'City of San Fernando' => ['San Fernando City'],
            'Science City of Muñoz' => ['Science City of Munoz', 'Muñoz', 'Munoz'],
            'Cagayan De Oro City' => ['Cagayan de Oro City', 'Cagayan de Oro'],
            'Cagayan de Oro City' => ['Cagayan De Oro City', 'Cagayan de Oro'],
        ];

        if (array_key_exists($cityName, $variants)) {
            $candidates = array_merge($candidates, $variants[$cityName]);
        }

        $candidates[] = ucfirst(strtolower($normalized));
        $candidates = array_values(array_unique(array_filter($candidates)));

        return $candidates;
    }
}
