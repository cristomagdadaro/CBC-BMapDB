<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');

            return;
        }

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_screen_cannot_be_rendered_if_support_is_disabled(): void
    {
        if (Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is enabled.');

            return;
        }

        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_new_users_can_register(): void
    {
        if (! Features::enabled(Features::registration())) {
            $this->markTestSkipped('Registration support is not enabled.');

            return;
        }

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_researcher_is_automatically_approved_on_registration(): void
    {
        $role = \App\Models\Role::firstOrCreate(['name' => \App\Enums\Role::RESEARCHER->value, 'guard_name' => 'web']);
        $institute = \App\Models\Institute::firstOrCreate(['name' => 'Test Institute']);
        $app = \App\Models\Application::firstOrCreate(['name' => 'Plant Breeders Map'], ['status' => 'true']);

        $response = $this->post('/register', [
            'fname' => 'Jane',
            'lname' => 'Researcher',
            'email' => 'researcher@example.com',
            'affiliation' => $institute->id,
            'role' => $role->id,
            'account_for' => $app->id,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $this->assertAuthenticated();
        $user = \App\Models\User::where('email', 'researcher@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole(\App\Enums\Role::RESEARCHER->value));

        // Account must be automatically approved
        $account = $user->accounts()->first();
        $this->assertNotNull($account);
        $this->assertNotNull($account->approved_at);

        // Mark email verified so 'verified' middleware passes, then verify researcher is redirected to Plant Breeders Map
        $user->markEmailAsVerified();
        $dashboardResponse = $this->actingAs($user)->get('/dashboard');
        $dashboardResponse->assertRedirect(route('projects.breedersmap.index'));

        $projectResponse = $this->actingAs($user)->get(route('projects.breedersmap.index'));
        $projectResponse->assertOk();
    }

    public function test_researcher_with_twg_account_is_redirected_to_twg_database(): void
    {
        $institute = \App\Models\Institute::firstOrCreate(['name' => 'Test Institute']);
        $app = \App\Models\Application::firstOrCreate(
            ['name' => 'Biotech TWG Database'],
            ['status' => 'true', 'url' => 'projects.twg.index']
        );
        $role = \App\Models\Role::firstOrCreate(
            ['name' => \App\Enums\Role::RESEARCHER->value],
            ['guard_name' => 'web']
        );

        $response = $this->post('/register', [
            'fname' => 'TWG',
            'lname' => 'Researcher',
            'email' => 'twg_researcher@example.com',
            'affiliation' => $institute->id,
            'role' => $role->id,
            'account_for' => $app->id,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $user = \App\Models\User::where('email', 'twg_researcher@example.com')->first();
        $this->assertNotNull($user);
        $user->markEmailAsVerified();

        $dashboardResponse = $this->actingAs($user)->get('/dashboard');
        $dashboardResponse->assertRedirect(route('projects.twg.index'));
    }

    public function test_focal_person_requires_admin_approval(): void
    {
        $this->assertRoleRequiresApproval(\App\Enums\Role::FOCAL_PERSON->value, 'focal@example.com');
    }

    public function test_breeder_requires_admin_approval(): void
    {
        $this->assertRoleRequiresApproval(\App\Enums\Role::BREEDER->value, 'breeder@example.com');
    }

    public function test_twg_manager_requires_admin_approval(): void
    {
        $this->assertRoleRequiresApproval(\App\Enums\Role::TWG_MANAGER->value, 'twg@example.com');
    }

    private function assertRoleRequiresApproval(string $roleName, string $email): void
    {
        $institute = \App\Models\Institute::firstOrCreate(['name' => 'Test Institute']);
        $app = \App\Models\Application::firstOrCreate(['name' => 'Plant Breeders Map'], ['status' => 'true']);
        $role = \App\Models\Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

        $response = $this->post('/register', [
            'fname' => 'Restricted',
            'lname' => 'User',
            'email' => $email,
            'affiliation' => $institute->id,
            'role' => $role->id,
            'account_for' => $app->id,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $user = \App\Models\User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole($roleName));

        // Must require approval - not in accounts (which filters whereNotNull approved_at)
        $this->assertNull($user->accounts()->first());

        // Must be in accountsPending with approved_at = null
        $pendingAccount = $user->accountsPending()->first();
        $this->assertNotNull($pendingAccount);
        $this->assertNull($pendingAccount->approved_at);
    }
}
