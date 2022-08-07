<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y');
    }

    static public function format(Resource $resource)
    {
        Resource::setReviews($resource);
        $resource->load('votes');

        $resource->position = [
            'lat' => $resource->latitude,
            'lng' => $resource->longitude
        ];
    }

    /**
     * Set reviews array and user resource_count and review_count
     * on each review of the resource reviews array
     *
     * @param Resource $resource
     * @return void
     */
    static public function setReviews(Resource $resource)
    {
        $resource->load('reviews');

        foreach ($resource->reviews as $review) {
            $review->user->review_count = $review->user->reviews()->count();
        }
    }
}
