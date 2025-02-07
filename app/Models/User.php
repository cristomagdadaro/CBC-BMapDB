<?php

namespace App\Models;

use App\Enums\Role as RoleEnum;
use App\Notifications\FocalPersonInvitationToBreederEmail;
use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;

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

    public function scopeOwnedBy(Builder $query, $user)
    {
        if ($this->ignoreUserBasedFiltratration)
            return $query;

        // If no user is provided, return no records (or handle as required)
        if (!$user) {
            return $query->whereRaw('1 = 0'); // No records
        }

        // If the user is not an admin or does not have the RESEARCHER role
        if (!$user->isAdmin() && !$user->hasRole(RoleEnum::RESEARCHER->value)) {
            $query->where('user_id', $user->id)
                ->orWhere('affiliation', $user->affiliation);
        }

        return $query;
    }


    public function sendEmailVerificationViaFocalPersonNotification(): void
    {
        //$this->notify(new VerifyEmail);

        $this->notify(new FocalPersonInvitationToBreederEmail);
    }
}
