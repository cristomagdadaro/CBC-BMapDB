<?php

namespace Tests\Feature\MapData;

use App\Models\Institute;
use App\Models\Location\City;
use App\Models\Location\Province;
use App\Models\Location\Region;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\PbMap\Enums\BreederType;
use Modules\PbMap\Models\Breeder;
use Modules\PbMap\Models\Commodity;
use Tests\TestCase;

class MapDataFiltersTest extends TestCase
{
    private const CITY_ID = 65;
    private const REGION_NAME = 'CAR';
    private const PROVINCE_NAME = 'Benguet';

    private const INSTITUTE_REGION = 'Aklan State University-Banga Campus';
    private const INSTITUTE_PROVINCE = 'Aurora State College of Technology';
    private const INSTITUTE_CITY = 'Camiguin Polytechnic State College';

    private int $cityId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedMapDataFixtures();
    }

    /** @test */
    public function base_endpoints_return_data_for_commodities_and_breeders(): void
    {
        foreach (['commodities', 'breeders'] as $dataType) {
            $this->assertMapDataResponse($dataType, '/api/map-data');
            $this->assertMapSummaryResponse($dataType, '/api/map-data/summary');
            $this->assertMapFilterOptionsResponse($dataType, '/api/map-data/filter-options');
        }
    }

    /**
     * @test
     * @dataProvider filterByProvider
     */
    public function filter_by_levels_are_supported_for_map_data_endpoints(string $filterBy): void
    {
        foreach (['commodities', 'breeders'] as $dataType) {
            $this->assertMapDataResponse($dataType, '/api/map-data', [
                'filter_by' => $filterBy,
            ]);

            $this->assertMapSummaryResponse($dataType, '/api/map-data/summary', [
                'filter_by' => $filterBy,
            ]);

            $this->assertMapFilterOptionsResponse($dataType, '/api/map-data/filter-options', [
                'filter_by' => $filterBy,
            ]);
        }
    }

    /**
     * @test
     * @dataProvider validFilterProvider
     */
    public function valid_filter_combinations_return_results(string $dataType, array $filters): void
    {
        $filters = $this->hydrateCityFilter($filters);
        $this->assertMapDataResponse($dataType, '/api/map-data', $filters, true);
        $this->assertMapSummaryResponse($dataType, '/api/map-data/summary', $filters, true);
    }

    /**
     * @test
     * @dataProvider invalidFilterProvider
     */
    public function invalid_filter_combinations_return_validation_or_empty_results(string $dataType, array $filters): void
    {
        $filters = $this->hydrateCityFilter($filters);
        $mapResponse = $this->getJson($this->buildUrl('/api/map-data', $dataType, $filters));
        $this->assertInvalidMapDataResponse($mapResponse);

        $summaryResponse = $this->getJson($this->buildUrl('/api/map-data/summary', $dataType, $filters));
        $this->assertInvalidSummaryResponse($summaryResponse);
    }

    /** @test */
    public function missing_data_type_returns_validation_error(): void
    {
        $this->getJson('/api/map-data')->assertStatus(422);
        $this->getJson('/api/map-data/summary')->assertStatus(422);
        $this->getJson('/api/map-data/filter-options')->assertStatus(422);
    }

    public static function filterByProvider(): array
    {
        return [
            ['region'],
            ['province'],
            ['city'],
            ['institute'],
        ];
    }

    public static function validFilterProvider(): array
    {
        return [
            ['commodities', [
                'filter_by' => 'region',
                'regions' => self::REGION_NAME,
            ]],
            ['commodities', [
                'filter_by' => 'region',
                'institutes' => self::INSTITUTE_REGION,
            ]],
            ['commodities', [
                'filter_by' => 'province',
                'provinces' => self::PROVINCE_NAME,
            ]],
            ['commodities', [
                'filter_by' => 'province',
                'institutes' => self::INSTITUTE_PROVINCE,
            ]],
            ['commodities', [
                'filter_by' => 'city',
                'cities' => '__CITY_ID__',
            ]],
            ['commodities', [
                'filter_by' => 'city',
                'institutes' => self::INSTITUTE_PROVINCE,
            ]],
            ['commodities', [
                'filter_by' => 'institute',
                'institutes' => self::INSTITUTE_CITY,
            ]],
            ['breeders', [
                'filter_by' => 'region',
                'regions' => self::REGION_NAME,
            ]],
            ['breeders', [
                'filter_by' => 'region',
                'institutes' => self::INSTITUTE_REGION,
            ]],
            ['breeders', [
                'filter_by' => 'province',
                'provinces' => self::PROVINCE_NAME,
            ]],
            ['breeders', [
                'filter_by' => 'province',
                'institutes' => self::INSTITUTE_PROVINCE,
            ]],
            ['breeders', [
                'filter_by' => 'city',
                'cities' => '__CITY_ID__',
            ]],
            ['breeders', [
                'filter_by' => 'city',
                'institutes' => self::INSTITUTE_PROVINCE,
            ]],
            ['breeders', [
                'filter_by' => 'institute',
                'institutes' => self::INSTITUTE_CITY,
            ]],
        ];
    }

    public static function invalidFilterProvider(): array
    {
        return [
            ['commodities', [
                'filter_by' => 'city',
                'regions' => self::REGION_NAME,
            ]],
            ['commodities', [
                'filter_by' => 'province',
                'cities' => '__CITY_ID__',
            ]],
            ['commodities', [
                'filter_by' => 'region',
                'provinces' => self::PROVINCE_NAME,
            ]],
            ['commodities', [
                'filter_by' => 'city',
                'cities' => '999999',
            ]],
            ['commodities', [
                'filter_by' => 'province',
                'provinces' => 'Unknown Province',
            ]],
            ['commodities', [
                'filter_by' => 'institute',
                'institutes' => 'Unknown Institute',
            ]],
            ['breeders', [
                'filter_by' => 'city',
                'regions' => self::REGION_NAME,
            ]],
            ['breeders', [
                'filter_by' => 'province',
                'cities' => '__CITY_ID__',
            ]],
            ['breeders', [
                'filter_by' => 'region',
                'provinces' => self::PROVINCE_NAME,
            ]],
            ['breeders', [
                'filter_by' => 'city',
                'cities' => '999999',
            ]],
            ['breeders', [
                'filter_by' => 'province',
                'provinces' => 'Unknown Province',
            ]],
            ['breeders', [
                'filter_by' => 'institute',
                'institutes' => 'Unknown Institute',
            ]],
        ];
    }

    private function assertMapDataResponse(string $dataType, string $endpoint, array $filters = [], bool $expectNonEmpty = false): void
    {
        $response = $this->getJson($this->buildUrl($endpoint, $dataType, $filters));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
            'metadata',
            'filter_options',
        ]);

        if ($expectNonEmpty) {
            $this->assertNotEmpty($response->json('data'));
        }
    }

    private function assertMapSummaryResponse(string $dataType, string $endpoint, array $filters = [], bool $expectNonEmpty = false): void
    {
        $response = $this->getJson($this->buildUrl($endpoint, $dataType, $filters));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'summary',
        ]);

        if ($expectNonEmpty) {
            $summary = $response->json('summary') ?? [];
            $this->assertNotEmpty($summary);
        }
    }

    private function assertMapFilterOptionsResponse(string $dataType, string $endpoint, array $filters = []): void
    {
        $response = $this->getJson($this->buildUrl($endpoint, $dataType, $filters));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'options',
        ]);
    }

    private function assertInvalidMapDataResponse($response): void
    {
        if ($response->status() === 422) {
            $response->assertJsonStructure(['errors']);
            return;
        }

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'data']);
        $this->assertEmpty($response->json('data'));
    }

    private function assertInvalidSummaryResponse($response): void
    {
        if ($response->status() === 422) {
            $response->assertJsonStructure(['errors']);
            return;
        }

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'summary']);

        $summary = $response->json('summary') ?? [];
        foreach ($summary as $value) {
            $this->assertEquals(0, $value);
        }
    }

    private function buildUrl(string $endpoint, string $dataType, array $filters = []): string
    {
        $params = array_merge(['data_type' => $dataType], $filters);
        return $endpoint . '?' . http_build_query($params);
    }

    private function seedMapDataFixtures(): void
    {
        $countryId = DB::table('loc_countries')->value('id');
        if (!$countryId) {
            $countryId = DB::table('loc_countries')->insertGetId([
                'country' => 'Philippines',
                'iso_code' => 'PH',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $region = Region::firstOrCreate([
            'regDesc' => self::REGION_NAME,
        ], [
            'regDescLong' => self::REGION_NAME,
            'country_id' => $countryId,
        ]);

        $province = Province::firstOrCreate([
            'provDesc' => self::PROVINCE_NAME,
        ], [
            'regDesc' => $region->regDesc,
        ]);

        $city = City::firstOrCreate([
            'cityDesc' => 'Test City',
            'provDesc' => $province->provDesc,
            'regDesc' => $region->regDesc,
        ], [
            'latitude' => '16.4023',
            'longitude' => '120.5960',
        ]);

        $this->cityId = (int) $city->id;

        $institutes = collect([
            self::INSTITUTE_REGION,
            self::INSTITUTE_PROVINCE,
            self::INSTITUTE_CITY,
        ])->map(function ($name) use ($city) {
            return Institute::firstOrCreate([
                'name' => $name,
            ], [
                'inst_type' => 'SUC',
                'geolocation' => $city->id,
                'website' => 'https://example.com',
                'email' => str_replace(' ', '', strtolower($name)) . '@example.com',
                'phone' => '09123456789',
            ]);
        })->values();

        foreach ($institutes as $index => $institute) {
            $this->createBreederWithCommodity($institute->id, $this->cityId, $index + 1);
        }
    }

    private function hydrateCityFilter(array $filters): array
    {
        if (isset($filters['cities']) && $filters['cities'] === '__CITY_ID__') {
            $filters['cities'] = (string) $this->cityId;
        }

        return $filters;
    }

    private function createBreederWithCommodity(int $instituteId, int $cityId, int $suffix): void
    {
        $user = User::factory()->create([
            'email' => "breeder{$suffix}@example.com",
            'affiliation' => $instituteId,
        ]);

        $breeder = Breeder::create([
            'fname' => 'Breeder',
            'mname' => null,
            'lname' => "{$suffix}",
            'suffix' => null,
            'mobile_no' => null,
            'email' => "breeder{$suffix}@example.com",
            'breeder_type' => BreederType::PUBLIC->value,
            'user_id' => $user->id,
            'affiliation' => $instituteId,
            'position' => 'Researcher',
            'educ_level' => null,
            'expertise' => null,
            'research_interest' => null,
            'geolocation' => $cityId,
            'photo' => null,
        ]);

        Commodity::create([
            'name' => 'Rice',
            'user_id' => $user->id,
            'breeder_id' => $breeder->id,
            'scientific_name' => null,
            'variety' => null,
            'accession' => null,
            'yield' => null,
            'population' => null,
            'maturity_period' => null,
            'description' => null,
            'photo' => null,
            'geolocation' => $cityId,
            'regulations' => null,
            'stress_resilience' => null,
            'approved_at' => now(),
        ]);
    }
}
