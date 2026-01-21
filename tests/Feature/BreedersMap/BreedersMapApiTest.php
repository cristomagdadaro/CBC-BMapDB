<?php

namespace Tests\Feature\BreedersMap;

use App\Models\Institute;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\Province;
use App\Models\Location\Region;
use App\Models\User;
use Illuminate\Support\Str;
use App\Enums\Role as RoleEnum;
use Modules\PbMap\Enums\BreederType;
use Modules\PbMap\Models\Breeder;
use Modules\PbMap\Models\Commodity;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BreedersMapApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

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
            'regDesc' => 'NCR',
        ], [
            'regDescLong' => 'National Capital Region',
            'country_id' => $countryId,
        ]);

        $province = Province::firstOrCreate([
            'provDesc' => 'Metro Manila',
        ], [
            'regDesc' => $region->regDesc,
        ]);

        $city = City::firstOrCreate([
            'cityDesc' => 'Manila',
            'provDesc' => $province->provDesc,
            'regDesc' => $region->regDesc,
        ], [
            'latitude' => '14.5995',
            'longitude' => '120.9842',
        ]);

        $institute = Institute::firstOrCreate([
            'name' => 'Test Institute',
        ], [
            'inst_type' => 'SUC',
            'geolocation' => $city->id,
            'website' => 'https://example.com',
            'email' => 'test-institute@example.com',
            'phone' => '09123456789',
        ]);

        $user = auth()->user();
        if ($user && !$user->affiliation) {
            $user->update(['affiliation' => $institute->id]);
        }

        if (!Breeder::query()->exists()) {
            Breeder::factory()->create([
                'affiliation' => $institute->id,
                'geolocation' => $city->id,
                'user_id' => $user?->id,
            ]);
        }

        if (!Commodity::query()->exists()) {
            $breeder = Breeder::query()->first();
            Commodity::factory()->create([
                'breeder_id' => $breeder->id,
                'geolocation' => $city->id,
                'user_id' => $breeder->user_id,
            ]);
        }
    }
    private function getInstituteId(): int
    {
        return (int) Institute::query()->value('id');
    }

    private function getDifferentInstituteId(int $excludeId): int
    {
        $existing = Institute::query()->where('id', '!=', $excludeId)->value('id');
        if ($existing) {
            return (int) $existing;
        }

        $cityId = $this->getCityId();
        $institute = Institute::create([
            'name' => 'Test Institute ' . Str::upper(Str::random(6)),
            'inst_type' => 'SUC',
            'geolocation' => $cityId,
            'website' => 'https://example.com',
            'email' => 'inst_' . Str::random(6) . '@example.com',
            'phone' => '09123456789',
        ]);

        return (int) $institute->id;
    }

    private function getCityId(): int
    {
        return (int) City::query()->value('id');
    }

    private function getBreeder(): Breeder
    {
        return Breeder::query()->firstOrFail();
    }

    private function getCommodity(): Commodity
    {
        return Commodity::query()->firstOrFail();
    }

    private function makeMobile(): string
    {
        return '09' . str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT);
    }

    private function createUserWithRole(string $role, ?int $affiliation = null): User
    {
        if (!Role::where('name', $role)->exists()) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        }

        $affiliationId = $affiliation ?: $this->getInstituteId();

        $user = User::factory()->create([
            'affiliation' => $affiliationId,
        ]);
        $user->assignRole($role);

        return $user;
    }

    /** @test */
    public function breeders_index_returns_data(): void
    {
        $response = $this->getJson('/api/breeders?page=1&sort=created_at&order=desc');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links', 'meta']);
    }

    /** @test */
    public function breeders_selection_returns_data(): void
    {
        $response = $this->getJson('/api/breeders/selections');
        if ($response->status() !== 200) {
            fwrite(STDERR, $response->getContent());
        }
        $response->assertStatus(200);
    }

    /** @test */
    public function breeders_show_returns_breeder(): void
    {
        $breeder = $this->getBreeder();
        $response = $this->getJson('/api/breeders/' . $breeder->id);
        $response->assertStatus(200);
        $this->assertEquals($breeder->id, $response['data']['id']);
    }

    /** @test */
    public function breeders_show_missing_returns_404(): void
    {
        $response = $this->getJson('/api/breeders/999999');
        $response->assertStatus(404);
    }

    /** @test */
    public function breeders_store_creates_record(): void
    {
        $payload = [
            'fname' => 'Test',
            'mname' => 'Q',
            'lname' => 'Breeder',
            'suffix' => null,
            'mobile_no' => $this->makeMobile(),
            'email' => 'breeder_' . Str::random(8) . '@example.com',
            'photo' => null,
            'breeder_type' => BreederType::PUBLIC->value,
            'affiliation' => $this->getInstituteId(),
            'position' => 'Research Specialist',
            'expertise' => 'Genetics',
            'research_interest' => 'Plant breeding',
            'educ_level' => 'Master\'s',
            'geolocation' => $this->getCityId(),
        ];

        $response = $this->postJson('/api/breeders', $payload);
        $response->assertStatus(201);
        $this->assertDatabaseHas('breeders', [
            'id' => $response['data']['id'],
            'email' => $payload['email'],
        ]);
    }

    /** @test */
    public function breeders_store_invalid_returns_422(): void
    {
        $response = $this->postJson('/api/breeders', [
            'fname' => 'OnlyFirstName',
        ]);
        $response->assertStatus(422);
    }

    /** @test */
    public function breeders_store_validation_messages_are_returned(): void
    {
        $response = $this->postJson('/api/breeders', [
            'fname' => 'Test',
            'lname' => 'User',
            'email' => 'invalid-email',
            'mobile_no' => '12345',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'mobile_no']);
        $this->assertEquals(
            'Invalid format. Format is 09XXXXXXXXX',
            $response->json('errors.mobile_no.0')
        );
    }

    /** @test */
    public function breeders_update_updates_record(): void
    {
        $breeder = $this->getBreeder();

        $payload = [
            'user_id' => $breeder->user_id,
            'fname' => $breeder->fname,
            'mname' => $breeder->mname,
            'lname' => $breeder->lname,
            'suffix' => $breeder->suffix,
            'mobile_no' => $breeder->mobile_no ?? $this->makeMobile(),
            'affiliation' => $breeder->affiliation,
            'photo' => $breeder->photo,
            'breeder_type' => $breeder->breeder_type,
            'email' => 'updated_' . Str::random(8) . '@example.com',
            'geolocation' => $breeder->geolocation,
            'position' => $breeder->position ?? 'Research Specialist',
            'expertise' => $breeder->expertise,
            'research_interest' => $breeder->research_interest,
            'educ_level' => $breeder->educ_level,
        ];

        $response = $this->putJson('/api/breeders/' . $breeder->id, $payload);
        $response->assertStatus(200);
        $this->assertDatabaseHas('breeders', [
            'id' => $breeder->id,
            'email' => $payload['email'],
        ]);
    }

    /** @test */
    public function breeders_update_invalid_returns_422(): void
    {
        $breeder = $this->getBreeder();
        $response = $this->putJson('/api/breeders/' . $breeder->id, [
            'email' => 'invalid-email',
        ]);
        $response->assertStatus(422);
    }

    /** @test */
    public function breeders_update_forbidden_for_non_owner_non_focal(): void
    {
        $instituteId = $this->getInstituteId();
        $breeder = Breeder::factory()->create([
            'affiliation' => $instituteId,
        ]);

        $otherInstituteId = $this->getDifferentInstituteId($instituteId);
        $otherUser = $this->createUserWithRole(RoleEnum::BREEDER->value, $otherInstituteId);
        $this->actingAs($otherUser);

        $response = $this->putJson('/api/breeders/' . $breeder->id, [
            'email' => 'forbidden_' . Str::random(6) . '@example.com',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function breeders_delete_deletes_record(): void
    {
        $breeder = Breeder::factory()->create();
        $response = $this->deleteJson('/api/breeders/' . $breeder->id);
        $response->assertStatus(200);
        $this->assertSoftDeleted('breeders', [
            'id' => $breeder->id,
        ]);
    }

    /** @test */
    public function breeders_multi_destroy_deletes_records(): void
    {
        $breederOne = Breeder::factory()->create();
        $breederTwo = Breeder::factory()->create();

        $response = $this->deleteJson('/api/breeders/delete', [
            'ids' => [$breederOne->id, $breederTwo->id],
        ]);

        $response->assertStatus(200);
        $this->assertSoftDeleted('breeders', ['id' => $breederOne->id]);
        $this->assertSoftDeleted('breeders', ['id' => $breederTwo->id]);
    }

    /** @test */
    public function commodities_index_returns_data(): void
    {
        $response = $this->getJson('/api/commodities?page=1&per_page=10&sort=id&order=asc');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links', 'meta']);
    }

    /** @test */
    public function commodities_show_returns_commodity(): void
    {
        $commodity = $this->getCommodity();
        $response = $this->getJson('/api/commodities/' . $commodity->id);
        $response->assertStatus(200);
        $this->assertEquals($commodity->id, $response['data']['id']);
    }

    /** @test */
    public function commodities_show_missing_returns_404(): void
    {
        $response = $this->getJson('/api/commodities/999999');
        $response->assertStatus(404);
    }

    /** @test */
    public function commodities_store_creates_record(): void
    {
        $breeder = $this->getBreeder();

        $payload = [
            'name' => 'Rice',
            'breeder_id' => $breeder->id,
            'scientific_name' => 'Oryza sativa',
            'accession' => 'ACC-' . Str::upper(Str::random(6)),
            'yield' => 1.5,
            'description' => 'Test commodity',
            'photo' => '/img/logo_cbc.png',
            'geolocation' => $breeder->geolocation,
            'regulations' => [
                [
                    'regulatory_body' => 'DA',
                    'registration_no' => 'REG-001',
                    'registration_date' => now()->toDateString(),
                ],
            ],
            'stress_resilience' => [
                [
                    'type' => 'Drought',
                    'stress' => 'Low water',
                    'reaction' => 'Tolerant',
                ],
            ],
        ];

        $response = $this->postJson('/api/commodities', $payload);
        $response->assertStatus(201);
        $this->assertDatabaseHas('commodities', [
            'id' => $response['data']['id'],
            'name' => $payload['name'],
        ]);
    }

    /** @test */
    public function commodities_store_invalid_returns_422(): void
    {
        $response = $this->postJson('/api/commodities', [
            'name' => 'Missing required fields',
        ]);
        $response->assertStatus(422);
    }

    /** @test */
    public function commodities_store_validation_messages_are_returned(): void
    {
        $response = $this->postJson('/api/commodities', [
            'name' => 'Invalid commodity',
            'yield' => 'not-a-number',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['breeder_id', 'scientific_name', 'yield']);
    }

    /** @test */
    public function commodities_update_updates_record(): void
    {
        $commodity = $this->getCommodity();

        $regulations = $commodity->regulations;
        if (is_string($regulations)) {
            $regulations = json_decode($regulations, true) ?: [];
        }

        $stressResilience = $commodity->stress_resilience;
        if (is_string($stressResilience)) {
            $stressResilience = json_decode($stressResilience, true) ?: [];
        }

        $payload = [
            'name' => $commodity->name,
            'breeder_id' => $commodity->breeder_id,
            'scientific_name' => $commodity->scientific_name,
            'accession' => $commodity->accession,
            'yield' => $commodity->yield,
            'description' => 'Updated description',
            'photo' => $commodity->photo,
            'geolocation' => $commodity->geolocation,
            'regulations' => $regulations ?? [],
            'stress_resilience' => $stressResilience ?? [],
        ];

        $response = $this->putJson('/api/commodities/' . $commodity->id, $payload);
        $response->assertStatus(200);
        $this->assertDatabaseHas('commodities', [
            'id' => $commodity->id,
            'description' => 'Updated description',
        ]);
    }

    /** @test */
    public function commodities_update_invalid_returns_422(): void
    {
        $commodity = $this->getCommodity();
        $response = $this->putJson('/api/commodities/' . $commodity->id, [
            'yield' => 'not-numeric',
        ]);
        $response->assertStatus(422);
    }

    /** @test */
    public function commodities_update_forbidden_for_non_owner_non_focal(): void
    {
        $commodity = Commodity::factory()->create();

        $otherInstituteId = $this->getDifferentInstituteId((int) $this->getInstituteId());
        $otherUser = $this->createUserWithRole(RoleEnum::BREEDER->value, $otherInstituteId);
        $this->actingAs($otherUser);

        $response = $this->putJson('/api/commodities/' . $commodity->id, [
            'yield' => 3.5,
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function commodities_delete_deletes_record(): void
    {
        $commodity = Commodity::factory()->create();
        $response = $this->deleteJson('/api/commodities/' . $commodity->id);
        $response->assertStatus(200);
        $this->assertSoftDeleted('commodities', [
            'id' => $commodity->id,
        ]);
    }

    /** @test */
    public function commodities_multi_destroy_deletes_records(): void
    {
        $commodityOne = Commodity::factory()->create();
        $commodityTwo = Commodity::factory()->create();

        $response = $this->deleteJson('/api/commodities/delete', [
            'ids' => [$commodityOne->id, $commodityTwo->id],
        ]);

        $response->assertStatus(200);
        $this->assertSoftDeleted('commodities', ['id' => $commodityOne->id]);
        $this->assertSoftDeleted('commodities', ['id' => $commodityTwo->id]);
    }

    /** @test */
    public function commodities_approve_sets_approved_at(): void
    {
        $commodity = Commodity::factory()->create(['approved_at' => null]);
        $response = $this->putJson('/api/commodities/' . $commodity->id . '/approve');
        $response->assertStatus(200);
        $this->assertDatabaseMissing('commodities', [
            'id' => $commodity->id,
            'approved_at' => null,
        ]);
    }

    /** @test */
    public function commodities_disapprove_sets_approved_at_to_null(): void
    {
        $commodity = Commodity::factory()->create(['approved_at' => now()]);
        $response = $this->putJson('/api/commodities/' . $commodity->id . '/disapprove');
        $response->assertStatus(200);
        $this->assertDatabaseHas('commodities', [
            'id' => $commodity->id,
            'approved_at' => null,
        ]);
    }

    /** @test */
    public function commodities_approve_forbidden_for_non_focal_non_admin(): void
    {
        $commodity = Commodity::factory()->create(['approved_at' => null]);

        $otherInstituteId = $this->getDifferentInstituteId((int) $this->getInstituteId());
        $breederUser = $this->createUserWithRole(RoleEnum::BREEDER->value, $otherInstituteId);
        $this->actingAs($breederUser);

        $response = $this->putJson('/api/commodities/' . $commodity->id . '/approve');
        $response->assertStatus(403);
    }

    /** @test */
    public function commodities_summary_returns_data(): void
    {
        $response = $this->getJson('/api/commodities/summary?geo_location_filter=region&geo_location_value=&is_exact=true');
        $response->assertStatus(200);
    }

    /** @test */
    public function breeders_summary_returns_data(): void
    {
        $response = $this->getJson('/api/breeders/summary?geo_location_filter=region&geo_location_value=&is_exact=true');
        $response->assertStatus(200);
    }

    /** @test */
    public function public_summary_endpoints_return_data(): void
    {
        $this->withoutMiddleware();
        $this->getJson('/api/commodities/summary?geo_location_filter=region&geo_location_value=&is_exact=true')
            ->assertStatus(200);
        $this->getJson('/api/breeders/summary?geo_location_filter=region&geo_location_value=&is_exact=true')
            ->assertStatus(200);
        $this->getJson('/api/commodities/priority')->assertStatus(200);
    }

    /** @test */
    public function map_data_endpoints_return_data(): void
    {
        $this->withoutMiddleware();
        $this->getJson('/api/map-data?data_type=breeders')->assertStatus(200);
        $this->getJson('/api/map-data/filter-options?data_type=breeders')->assertStatus(200);
        $this->getJson('/api/map-data/summary?data_type=breeders')->assertStatus(200);
        $this->getJson('/api/map-data/orbit-items?data_type=breeders&city_ids=' . $this->getCityId())->assertStatus(200);
    }

    /** @test */
    public function pagination_and_filtering_permutations_work(): void
    {
        $breeder = $this->getBreeder();

        $breedersResponse = $this->getJson('/api/breeders?page=1&per_page=5&sort=created_at&order=asc&search=a');
        $breedersResponse->assertStatus(200);
        $this->assertEquals(5, $breedersResponse['meta']['per_page']);

        $commoditiesResponse = $this->getJson('/api/commodities?filter_by_parent_id=' . $breeder->id . '&filter_by_parent_column=breeder_id&per_page=5&sort=id&order=asc');
        $commoditiesResponse->assertStatus(200);
        $this->assertEquals(5, $commoditiesResponse['meta']['per_page']);
    }

    /** @test */
    public function dashboard_endpoints_return_data(): void
    {
        $this->getJson('/api/breeders-dashboard/overview')->assertStatus(200);
        $this->getJson('/api/breeders-dashboard/recent')->assertStatus(200);
        $this->getJson('/api/breeders-dashboard/my-stats')->assertStatus(200);
    }

    /** @test */
    public function dashboard_overview_supports_scope_filters(): void
    {
        $instituteId = $this->getInstituteId();
        $otherInstituteId = $this->getDifferentInstituteId($instituteId);

        $admin = $this->createUserWithRole(RoleEnum::ADMIN->value, $instituteId);
        $this->actingAs($admin);

        $this->getJson('/api/breeders-dashboard/overview?scope_by=all')
            ->assertStatus(200)
            ->assertJsonStructure(['totals', 'charts', 'scope']);

        $this->getJson('/api/breeders-dashboard/overview?scope_by=public')
            ->assertStatus(200)
            ->assertJsonStructure(['totals', 'charts', 'scope']);

        $this->getJson('/api/breeders-dashboard/overview?scope_by=institute&institute_id=' . $otherInstituteId)
            ->assertStatus(200)
            ->assertJsonStructure(['totals', 'charts', 'scope']);

        $this->getJson('/api/breeders-dashboard/overview?scope_by=owned')
            ->assertStatus(200)
            ->assertJsonStructure(['totals', 'charts', 'scope']);
    }

    /** @test */
    public function dashboard_overview_defaults_by_role(): void
    {
        $instituteId = $this->getInstituteId();

        $breeder = $this->createUserWithRole(RoleEnum::BREEDER->value, $instituteId);
        $this->actingAs($breeder);
        $breederResponse = $this->getJson('/api/breeders-dashboard/overview');
        $breederResponse->assertStatus(200);
        $this->assertEquals('owned', $breederResponse->json('scope.scope_by'));

        $focal = $this->createUserWithRole(RoleEnum::FOCAL_PERSON->value, $instituteId);
        $this->actingAs($focal);
        $focalResponse = $this->getJson('/api/breeders-dashboard/overview');
        $focalResponse->assertStatus(200);
        $this->assertEquals('institute', $focalResponse->json('scope.scope_by'));

        $researcher = $this->createUserWithRole(RoleEnum::RESEARCHER->value, $instituteId);
        $this->actingAs($researcher);
        $researcherResponse = $this->getJson('/api/breeders-dashboard/overview');
        $researcherResponse->assertStatus(200);
        $this->assertEquals('public', $researcherResponse->json('scope.scope_by'));
    }

    /** @test */
    public function dashboard_recent_supports_scope_filters(): void
    {
        $instituteId = $this->getInstituteId();
        $admin = $this->createUserWithRole(RoleEnum::ADMIN->value, $instituteId);
        $this->actingAs($admin);

        $this->getJson('/api/breeders-dashboard/recent?scope_by=all')
            ->assertStatus(200)
            ->assertJsonStructure(['breeders', 'commodities']);

        $this->getJson('/api/breeders-dashboard/recent?scope_by=public')
            ->assertStatus(200)
            ->assertJsonStructure(['breeders', 'commodities']);

        $this->getJson('/api/breeders-dashboard/recent?scope_by=owned')
            ->assertStatus(200)
            ->assertJsonStructure(['breeders', 'commodities']);
    }

    /** @test */
    public function dashboard_scope_rejects_invalid_values_and_falls_back(): void
    {
        $instituteId = $this->getInstituteId();

        $breeder = $this->createUserWithRole(RoleEnum::BREEDER->value, $instituteId);
        $this->actingAs($breeder);
        $response = $this->getJson('/api/breeders-dashboard/overview?scope_by=invalid');
        $response->assertStatus(200);
        $this->assertEquals('owned', $response->json('scope.scope_by'));

        $researcher = $this->createUserWithRole(RoleEnum::RESEARCHER->value, $instituteId);
        $this->actingAs($researcher);
        $response = $this->getJson('/api/breeders-dashboard/overview?scope_by=owned');
        $response->assertStatus(200);
        $this->assertEquals('public', $response->json('scope.scope_by'));
    }

    /** @test */
    public function dashboard_scope_all_is_admin_only(): void
    {
        $instituteId = $this->getInstituteId();

        $breeder = $this->createUserWithRole(RoleEnum::BREEDER->value, $instituteId);
        $this->actingAs($breeder);
        $response = $this->getJson('/api/breeders-dashboard/overview?scope_by=all');
        $response->assertStatus(200);
        $this->assertEquals('owned', $response->json('scope.scope_by'));

        $focal = $this->createUserWithRole(RoleEnum::FOCAL_PERSON->value, $instituteId);
        $this->actingAs($focal);
        $response = $this->getJson('/api/breeders-dashboard/overview?scope_by=all');
        $response->assertStatus(200);
        $this->assertEquals('institute', $response->json('scope.scope_by'));
    }

    /** @test */
    public function dashboard_institute_scope_defaults_to_user_affiliation_when_missing_id(): void
    {
        $instituteId = $this->getInstituteId();
        $focal = $this->createUserWithRole(RoleEnum::FOCAL_PERSON->value, $instituteId);
        $this->actingAs($focal);

        $response = $this->getJson('/api/breeders-dashboard/overview?scope_by=institute');
        $response->assertStatus(200);
        $this->assertEquals('institute', $response->json('scope.scope_by'));
        $this->assertEquals($instituteId, $response->json('scope.institute_id'));
    }
}
