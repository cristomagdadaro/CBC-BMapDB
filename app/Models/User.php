<?php

namespace App\Models;

use App\Enums\Role as RoleEnum;
use App\Notifications\FocalPersonInvitationToBreederEmail;
use App\Traits\OwnedByTrait;
use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Modules\PbMap\Models\Breeder;
use Modules\TwgDb\Models\TWGExpert;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasProfilePhoto, HasTeams, Notifiable, TwoFactorAuthenticatable, HasRoles, SoftDeletes, OwnedByTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'suffix',
        'email',
        'mobile_no',
        'password',
        'affiliation',
        'email_verified_at',
        'google_id',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected array $searchable = [
        'id',
        'fname',
        'mname',
        'lname',
        'suffix',
        'email',
        'mobile_no',
        'affiliation',
        'email_verified_at',
        'deleted_at'
    ];

    protected array $notifMessage = [
        'created' => 'User created successfully.',
        'updated' => 'User updated successfully.',
        'deleted' => 'User deleted successfully.',
        'restored' => 'User restored successfully.',
        'forceDeleted' => 'User permanently deleted.',
        'emptyTrash' => 'User deleted successfully.',
        'notFound' => 'User not found.',
        'unknown' => 'Unknown error, action failed.',
    ];

    public function getNotifMessage($action = null): string
    {
        return $this->notifMessage[$action];
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('g:i a M j, Y');
    }

    public function breeder(): HasMany
    {
        return $this->hasMany(Breeder::class, 'user_id', 'id');
    }

    public function makeBreeder(array $request, $olderBreeder): Model
    {
        $attributes =  array_merge(
            $request, // Ensure request is an array
            [
                'user_id' => $this->id,
                'geolocation' => $this->affiliated->id,
            ]
        );

        return $this->breeder()->firstOrCreate($attributes);
    }

    public function twgexpert(): HasMany
    {
        return $this->hasMany(TWGExpert::class, 'user_id', 'id');
    }

    public function accountFor(): HasMany
    {
        return $this->hasMany(Accounts::class, 'user_id', 'id')->whereNotNull('approved_at')->with('application:id,name,url,status');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Accounts::class, 'user_id', 'id')->whereNotNull('approved_at')->with('application:id,name,url,status');
    }

    public function accountsPending(): HasMany
    {
        return $this->hasMany(Accounts::class, 'user_id', 'id')->whereNull('approved_at')->with('application:id,name,url,status');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')->with('permissions:id,name');
    }

    public function getSearchable(): array
    {
        return $this->searchable;
    }

    public function getRole(): string | null
    {
        return $this->roles->pluck('name')->first();
    }

    public function getPermissions(): array
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RoleEnum::ADMIN->value);
    }

    public function isBreeder(): bool
    {
        return $this->hasRole(RoleEnum::BREEDER->value);
    }

    public function isResearcher(): bool
    {
        return $this->hasRole(RoleEnum::RESEARCHER->value);
    }

    public function dataView(): HasMany
    {
        return $this->hasMany(DataView::class, 'user_account_id');
    }


    public function approve(int | array $id = null): void
    {
        if ($id) {
            if (is_int($id))
                $this->accounts()->create([
                    'user_id' => $this->id,
                    'app_id' => $id,
                    'approved_at' => now(),
                ]);
            else
                foreach ($id as $key => $value) {
                    $this->accounts()->create([
                        'user_id' => $this->id,
                        'app_id' => $value,
                        'approved_at' => now(),
                    ]);
                }
        }
    }

    public function affiliated(): BelongsTo
    {
        return $this->belongsTo(Institute::class, 'affiliation', 'id');
    }

    public function sendEmailVerificationViaFocalPersonNotification(): void
    {
        //$this->notify(new VerifyEmail);

        $this->notify(new FocalPersonInvitationToBreederEmail);
    }
}
