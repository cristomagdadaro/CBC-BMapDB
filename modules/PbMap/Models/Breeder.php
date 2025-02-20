<?php

namespace Modules\PbMap\Models;

use App\Models\BaseModel;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Breeder extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'breeders';
    // user_id - id of the creator

    protected $fillable = [
        'user_id',

        'fname',
        'mname',
        'lname',
        'suffix',
        'email',
        'mobile_no',
        'affiliation',
        'email_verified_at',

        'geolocation',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    protected array $searchable = [
        'breeders.id',
        'user_id',
        'fname',
        'mname',
        'lname',
        'suffix',
        'affiliation',
        'geolocation',
        'mobile_no',
        'email',
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

    // Scope a query to only include commodities that belong to the same institute
    public function scopeOfModel($query)
    {
        return $query->where('user_id', auth()->id());
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
                    'password' => bcrypt('defaultpassword'), // Change this as needed
                ]);

                $breeder->user_id = $user->id;
            }
        });
    }
}
