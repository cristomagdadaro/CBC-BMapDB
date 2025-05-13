<?php

namespace Modules\PbMap\Models;

use App\Enums\DefaultPassword;
use App\Models\BaseModel;
use App\Models\Institute;
use App\Models\Location\City;
use App\Models\User;
use App\Traits\OwnedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Breeder extends BaseModel
{
    use HasFactory, SoftDeletes, OwnedByTrait;

    protected $table = 'breeders';

    protected $fillable = [
        'user_id',
        'fname',
        'mname',
        'lname',
        'suffix',
        'email',
        'mobile_no',
        'affiliation',
        'position',
        'educ_level',
        'breeder_type',
        'expertise',
        'research_interest',
        'geolocation',
        'photo'
    ];

    protected $guarded = ['id'];

    //protected $hidden = ['photo'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected array $searchable = [
        'breeders.id',
        'breeders.user_id',
        'breeders.fname',
        'breeders.mname',
        'breeders.lname',
        'breeders.suffix',
        'breeders.affiliation',
        'breeders.position',
        'breeders.educ_level',
        'breeders.expertise',
        'breeders.research_interest',
        'breeders.geolocation',
        'breeders.breeder_type',
        'breeders.mobile_no',
        'breeders.email',
        'breeders.photo',
        'breeders.created_at',
        'breeders.updated_at',
        'breeders.deleted_at',
    ];

    protected array $notifMessage = [
        'created' => 'Breeder created successfully.',
        'updated' => 'Breeder updated successfully.',
        'deleted' => 'Breeder deleted successfully.',
        'restored' => 'Breeder restored successfully.',
        'forceDeleted' => 'Breeder permanently deleted.',
        'emptyTrash' => 'Breeder deleted successfully.',
        'notFound' => 'Breeder not found.',
        'unknown' => 'Unknown error, action failed.',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class, 'breeder_id', 'id')->with('location','breeder');
    }

    public function affiliated(): BelongsTo
    {
        return $this->belongsTo(Institute::class, 'affiliation', 'id');
    }

    /**
     * Automatically create a user account for all breeder
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($breeder) {
            if (!$breeder->user_id) {
                $user = User::create([
                    'name' => $breeder->fname . ' ' . $breeder->lname,
                    'email' => $breeder->email ?? fake()->unique()->safeEmail(),
                    'password' => bcrypt(DefaultPassword::Value->value), // Change this as needed
                ]);

                $breeder->user_id = $user->id;
            }
        });
    }
}
