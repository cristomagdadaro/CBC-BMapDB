<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role(RoleEnum::ADMIN->value, RoleEnum::ADMIN->value, [

        ])->description('Administrator users can perform any action across all databases.');

        Jetstream::role(RoleEnum::TWG_ADMIN->value, RoleEnum::TWG_ADMIN->value, [
            PermissionEnum::CREATE_TWG_EXPERT->value,
            PermissionEnum::UPDATE_TWG_EXPERT->value,
            PermissionEnum::READ_TWG_EXPERT->value,

            PermissionEnum::CREATE_TWG_SERVICE->value,
            PermissionEnum::UPDATE_TWG_SERVICE->value,
            PermissionEnum::READ_TWG_SERVICE->value,

            PermissionEnum::CREATE_TWG_PRODUCT->value,
            PermissionEnum::UPDATE_TWG_PRODUCT->value,
            PermissionEnum::READ_TWG_PRODUCT->value,

            PermissionEnum::CREATE_TWG_PROJECT->value,
            PermissionEnum::UPDATE_TWG_PROJECT->value,
            PermissionEnum::READ_TWG_PROJECT->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ])->description('Expert have the ability to read, create, and update data in TWG Database.');

        Jetstream::role(RoleEnum::FOCAL_PERSON->value, RoleEnum::FOCAL_PERSON->value, [
            PermissionEnum::CREATE_BREEDER->value,
            PermissionEnum::UPDATE_BREEDER->value,
            PermissionEnum::READ_BREEDER->value,

            PermissionEnum::CREATE_COMMODITY->value,
            PermissionEnum::UPDATE_COMMODITY->value,
            PermissionEnum::READ_COMMODITY->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ])->description('Focal Person is in-charge of monitoring an institutes account and have the ability to read, create, and update data under its responsibility.');

        Jetstream::role(RoleEnum::BREEDER->value, RoleEnum::BREEDER->value, [
            PermissionEnum::CREATE_COMMODITY->value,
            PermissionEnum::UPDATE_COMMODITY->value,
            PermissionEnum::READ_COMMODITY->value,
            PermissionEnum::READ_BREEDER->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ])->description('Breeder have the ability to read, create, and update commodity data.');

        Jetstream::role(RoleEnum::RESEARCHER->value, RoleEnum::RESEARCHER->value, [
            PermissionEnum::READ_BREEDER->value,
            PermissionEnum::READ_COMMODITY->value,

            PermissionEnum::READ_TWG_EXPERT->value,
            PermissionEnum::READ_TWG_SERVICE->value,
            PermissionEnum::READ_TWG_PRODUCT->value,
            PermissionEnum::READ_TWG_PROJECT->value,

            PermissionEnum::CREATE_APP_ACCOUNT->value
        ])->description('Researcher can only read data in the system.');
    }
}
