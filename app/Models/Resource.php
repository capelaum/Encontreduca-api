<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function votes()
    {
        return $this->hasMany(ResourceVote::class);
    }

    public static function getAllResources()
    {
        $resources = DB::select("
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
        ");

        return $resources;
    }

    public static function getResourceReviews(int $resourceId)
    {
        $reviews = DB::select("
        SELECT
            r . id,
            r . user_id AS userId,
            r . rating,
            u . name AS author,
            u . avatar_url AS authorAvatar,
            r . resource_id AS resourceId,
            r . comment,
            r . rating,
            DATE_FORMAT(r . updated_at, '%d/%m/%Y') AS updatedAt
        FROM reviews r
        JOIN users u ON u . id = r . user_id
        WHERE r . resource_id = :resourceId
        ", ['resourceId' => $resourceId]);

        foreach ($reviews as $review) {
            $userReviewCount = DB::select("
                SELECT
                COUNT(*) as count
                FROM reviews r
                WHERE r . user_id = :userId
            ", ['userId' => $review->userId]);

            $review->authorReviewCount = $userReviewCount[0]->count;
        }

        return $reviews;
    }

    public static function getResourceVotes(int $resourceId)
    {
        return DB::select("
        SELECT
            v . id,
            v . resource_id AS resourceId,
            v . user_id AS userId,
            u . name AS author,
            u . avatar_url AS authorAvatar,
            v . vote,
            v . justification
        FROM resource_votes v
        JOIN users u ON u . id = v . user_id
        WHERE v . resource_id = :resourceId
        ", ['resourceId' => $resourceId]);
    }

    public static function resourceResponseArray(Resource $resource): array
    {
        return [
            'id' => $resource->id,
            'userId' => $resource->user_id,
            'author' => $resource->user->name,
            'categoryId' => $resource->category_id,
            'categoryName' => $resource->category->name,
            'name' => $resource->name,
            'address' => $resource->address,
            'latitude' => $resource->latitude,
            'longitude' => $resource->longitude,
            'website' => $resource->website,
            'phone' => $resource->phone,
            'cover' => $resource->cover,
            'approved' => $resource->approved,
            'createdAt' => date('d/m/Y', strtotime($resource->created_at)),
            'updatedAt' => date('d/m/Y', strtotime($resource->updated_at)),
        ];
    }

    public static function resourceArray(Resource $resource): array
    {
        return [
            'id' => $resource->id,
            'user_id' => $resource->user_id,
            'category_id' => $resource->category_id,
            'name' => $resource->name,
            'latitude' => $resource->latitude,
            'longitude' => $resource->longitude,
            'address' => $resource->address,
            'website' => $resource->website,
            'phone' => $resource->phone,
            'cover' => $resource->cover,
            'approved' => $resource->approved,
        ];
    }

    public static function resourceReviewsResponseArray(Resource $resource): array
    {
        return [

        ];
    }
}
