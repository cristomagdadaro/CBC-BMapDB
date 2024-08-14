<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes, HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, HasRoles;

    protected $table = 'users';

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
        'affiliation'
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
        'created_at',
        'updated_at',
        'profile_photo_url',
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

    public function twgexpert(): HasMany
    {
        return $this->hasMany(TWGExpert::class, 'user_id', 'id');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Accounts::class, 'user_id', 'id')->whereNotNull('approved_at');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')->with('permissions');
    }

    public function getSearchable(): array
    {
        return $this->searchable;
    }

    public function getRole(): string
    {
        return $this->roles->pluck('name')->first();
    }

    public function getPermissions(): array
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(\App\Enums\Role::ADMIN->value);
    }

    public function isBreeder(): bool
    {
        return $this->hasRole(\App\Enums\Role::BREEDER->value);
    }

    public function isResearcher(): bool
    {
        return $this->hasRole(\App\Enums\Role::RESEARCHER->value);
    }

    public function approve($id = null): void
    {
        if ($id) {
            $this->accounts()->create([
                'user_id' => $this->id,
                'app_id' => $id,
                'approved_at' => now(),
            ]);
        }
    }
}
