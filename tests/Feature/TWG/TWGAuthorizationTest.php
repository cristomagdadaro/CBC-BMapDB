<?php

namespace Tests\Feature\TWG;

use App\Enums\Applications;
use App\Enums\Role as RoleEnum;
use App\Models\Application;
use App\Models\Institute;
use App\Models\Location\City;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Modules\TwgDb\Models\TWGExpert;
use Modules\TwgDb\Models\TWGProduct;
use Modules\TwgDb\Models\TWGProject;
use Modules\TwgDb\Models\TWGService;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TWGAuthorizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->ensureTwgApplication();
    }

    private function ensureTwgApplication(): void
    {
        Application::firstOrCreate([
            'name' => Applications::TWG_DATABASE->value,
        ], [
            'description' => Applications::TWG_DATABASE_DESC->value,
            'url' => Applications::TWG_DATABASE_ROUTE->value,
            'icon' => Applications::TWG_DATABASE_LOGO->value,
            'status' => true,
        ]);
    }

    private function createInstitute(string $name): Institute
    {
        $cityId = City::query()->value('id');

        return Institute::firstOrCreate([
            'name' => $name,
        ], [
            'inst_type' => 'SUC',
            'geolocation' => $cityId,
            'website' => 'https://example.com',
            'email' => Str::slug($name) . '@example.com',
            'phone' => '09123456789',
        ]);
    }

    private function createUserWithRole(string $role, int $affiliationId): User
    {
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);

        $user = User::factory()->create([
            'affiliation' => $affiliationId,
        ]);

        $user->assignRole($role);

        return $user;
    }

    private function makeMobile(): string
    {
        return '09' . str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT);
    }

    private function createExpert(int $userId, int $institutionId, ?string $name = null): TWGExpert
    {
        $expertName = $name ?? ('Expert ' . Str::upper(Str::random(6)));

        return TWGExpert::create([
            'user_id' => $userId,
            'institution' => $institutionId,
            'name' => $expertName,
            'position' => 'Researcher',
            'educ_level' => "Doctoral",
            'expertise' => 'Genetics',
            'research_interest' => 'Plant breeding',
            'mobile' => $this->makeMobile(),
            'email' => Str::slug($expertName) . '_' . Str::random(5) . '@example.com',
        ]);
    }

    private function createProject(int $userId, int $institutionId, int $leaderId): TWGProject
    {
        return TWGProject::create([
            'user_id' => $userId,
            'institution' => $institutionId,
            'title' => 'Project ' . Str::upper(Str::random(6)),
            'objective' => 'Objective ' . Str::random(6),
            'expected_output' => 'Output ' . Str::random(6),
            'project_leader' => $leaderId,
            'funding_agency' => 'DA-BAR',
            'duration' => '1 year',
            'status' => 'Active',
        ]);
    }

    private function createProduct(int $userId, int $institutionId): TWGProduct
    {
        return TWGProduct::create([
            'user_id' => $userId,
            'institution' => $institutionId,
            'name' => 'Product ' . Str::upper(Str::random(6)),
            'brand' => 'Brand X',
            'purpose' => 'Testing',
            'cost' => '100',
        ]);
    }

    private function createService(int $userId, int $institutionId, int $officerId): TWGService
    {
        return TWGService::create([
            'user_id' => $userId,
            'institution' => $institutionId,
            'type' => 'Research',
            'purpose' => 'Diagnostics',
            'direct_beneficiaries' => 'Farmers',
            'indirect_beneficiaries' => 'Consumers',
            'officer_in_charge' => $officerId,
            'cost' => '100',
        ]);
    }

    /** @test */
    public function twg_expert_update_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $expert = $this->createExpert($otherUser->id, $instB->id);

        Sanctum::actingAs($manager);

        $payload = [
            'user_id' => $manager->id,
            'name' => 'Updated ' . Str::upper(Str::random(5)),
            'position' => 'Updated Position',
            'educ_level' => "Doctoral",
            'expertise' => 'Genetics',
            'institution' => $instA->id,
            'research_interest' => 'Plant breeding',
            'mobile' => $this->makeMobile(),
            'email' => 'updated_' . Str::random(6) . '@example.com',
        ];

        $response = $this->putJson('/api/twg/experts/' . $expert->id, $payload);
        $response->assertStatus(403);
    }

    /** @test */
    public function twg_expert_delete_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $expert = $this->createExpert($otherUser->id, $instB->id);

        Sanctum::actingAs($manager);

        $response = $this->deleteJson('/api/twg/experts/' . $expert->id);
        $response->assertStatus(403);
    }

    /** @test */
    public function twg_expert_multi_delete_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $expertA = $this->createExpert($otherUser->id, $instB->id);
        $expertB = $this->createExpert($otherUser->id, $instB->id);

        Sanctum::actingAs($manager);

        $response = $this->deleteJson('/api/twg/experts/delete', [
            'ids' => [$expertA->id, $expertB->id],
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function twg_project_update_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $leader = $this->createExpert($otherUser->id, $instB->id);
        $project = $this->createProject($otherUser->id, $instB->id, $leader->id);

        Sanctum::actingAs($manager);

        $payload = [
            'institution' => $instA->id,
            'title' => 'Updated ' . Str::upper(Str::random(5)),
            'objective' => 'Updated objective',
            'expected_output' => 'Updated output',
            'project_leader' => $leader->id,
            'funding_agency' => 'DA-BAR',
            'duration' => '2 years',
            'status' => 'Active',
        ];

        $response = $this->putJson('/api/twg/projects/' . $project->id, $payload);
        $response->assertStatus(403);
    }

    /** @test */
    public function twg_project_delete_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $leader = $this->createExpert($otherUser->id, $instB->id);
        $project = $this->createProject($otherUser->id, $instB->id, $leader->id);

        Sanctum::actingAs($manager);

        $response = $this->deleteJson('/api/twg/projects/' . $project->id);
        $response->assertStatus(403);
    }

    /** @test */
    public function twg_project_multi_delete_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $leader = $this->createExpert($otherUser->id, $instB->id);
        $projectA = $this->createProject($otherUser->id, $instB->id, $leader->id);
        $projectB = $this->createProject($otherUser->id, $instB->id, $leader->id);

        Sanctum::actingAs($manager);

        $response = $this->deleteJson('/api/twg/projects/delete', [
            'ids' => [$projectA->id, $projectB->id],
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function twg_product_update_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $product = $this->createProduct($otherUser->id, $instB->id);

        Sanctum::actingAs($manager);

        $payload = [
            'institution' => $instA->id,
            'name' => 'Updated ' . Str::upper(Str::random(5)),
            'brand' => 'Updated Brand',
            'purpose' => 'Updated purpose',
            'cost' => '250',
        ];

        $response = $this->putJson('/api/twg/products/' . $product->id, $payload);
        $response->assertStatus(403);
    }

    /** @test */
    public function twg_product_delete_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $product = $this->createProduct($otherUser->id, $instB->id);

        Sanctum::actingAs($manager);

        $response = $this->deleteJson('/api/twg/products/' . $product->id);
        $response->assertStatus(403);
    }

    /** @test */
    public function twg_product_multi_delete_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $productA = $this->createProduct($otherUser->id, $instB->id);
        $productB = $this->createProduct($otherUser->id, $instB->id);

        Sanctum::actingAs($manager);

        $response = $this->deleteJson('/api/twg/products/delete', [
            'ids' => [$productA->id, $productB->id],
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function twg_service_update_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $officer = $this->createExpert($otherUser->id, $instB->id);
        $service = $this->createService($otherUser->id, $instB->id, $officer->id);

        Sanctum::actingAs($manager);

        $payload = [
            'institution' => $instA->id,
            'type' => 'Updated type',
            'purpose' => 'Updated purpose',
            'direct_beneficiaries' => 'Farmers',
            'indirect_beneficiaries' => 'Consumers',
            'officer_in_charge' => $officer->id,
            'cost' => '200',
        ];

        $response = $this->putJson('/api/twg/services/' . $service->id, $payload);
        $response->assertStatus(403);
    }

    /** @test */
    public function twg_service_delete_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $officer = $this->createExpert($otherUser->id, $instB->id);
        $service = $this->createService($otherUser->id, $instB->id, $officer->id);

        Sanctum::actingAs($manager);

        $response = $this->deleteJson('/api/twg/services/' . $service->id);
        $response->assertStatus(403);
    }

    /** @test */
    public function twg_service_multi_delete_forbidden_outside_institution(): void
    {
        $instA = Institute::query()->firstOrFail();
        $instB = $this->createInstitute('Other Institute');

        $manager = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instA->id);
        $otherUser = $this->createUserWithRole(RoleEnum::TWG_MANAGER->value, $instB->id);
        $officer = $this->createExpert($otherUser->id, $instB->id);
        $serviceA = $this->createService($otherUser->id, $instB->id, $officer->id);
        $serviceB = $this->createService($otherUser->id, $instB->id, $officer->id);

        Sanctum::actingAs($manager);

        $response = $this->deleteJson('/api/twg/services/delete', [
            'ids' => [$serviceA->id, $serviceB->id],
        ]);
        $response->assertStatus(403);
    }
}
