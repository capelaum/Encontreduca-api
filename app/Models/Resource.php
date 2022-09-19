<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'latitude',
        'longitude',
        'address',
        'website',
        'phone',
        'cover',
        'approved'
    ];

    protected $with = ['category', 'user'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ResourceVote::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(ResourceComplaint::class);
    }

    public function changes(): HasMany
    {
        return $this->hasMany(ResourceChange::class);
    }

    /**
     * Query all Resources formatted.
     *
     * @return array
     */
    static public function getAllResources(): array
    {
        return DB::select("
        SELECT
            r.id,
            r.user_id AS userId,
            u.name AS author,
            r.category_id AS categoryId,
            c.name AS categoryName,
            r.name,
            r.address,
            r.latitude,
            r.longitude,
            r.website,
            r.phone,
            r.cover,
            r.approved,
            DATE_FORMAT(r.created_at, '%d/%m/%Y') AS createdAt,
            DATE_FORMAT(r.updated_at, '%d/%m/%Y') AS updatedAt
        FROM resources r
        JOIN users u ON u.id = r.user_id
        JOIN categories c ON c.id = r.category_id
        ORDER BY r.id
        ");
    }

    static public function deleteResource(Resource $resource)
    {
        $resource->changes()->each(function ($change) {
            if ($change->field === 'cover') {
                $cloudinaryFolder = config('app.cloudinary_folder');

                $coverUrlArray = explode('/', $change->new_value);
                $publicId = explode('.', end($coverUrlArray))[0];

                cloudinary()->destroy("$cloudinaryFolder/covers/changes/$publicId");
            }

            $change->delete();
        });

        $resource->reviews()->each(function ($review) {
            $review->complaints()->delete();

            $review->delete();
        });

        $resource->complaints()->delete();
        $resource->votes()->delete();

        ResourceUser::where('resource_id', $resource->id)->delete();

        $resource->delete();
    }
}
