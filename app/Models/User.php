<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use ProtoneMedia\LaravelVerifyNewEmail\MustVerifyNewEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, MustVerifyNewEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar_url',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // protected $with = ['resources'];

    public function savedResources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function resourceComplaints(): HasMany
    {
        return $this->hasMany(ResourceComplaint::class);
    }

    public function resourceChanges(): HasMany
    {
        return $this->hasMany(ResourceChange::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function reviewComplaints(): HasMany
    {
        return $this->hasMany(ReviewComplaint::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ResourceVote::class);
    }

    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class);
    }

    public function supports(): HasMany
    {
        return $this->hasMany(Support::class);
    }

    static public function deleteUser(User $user)
    {
        $user->resources()->each(function ($resource) {
            $resource->user_id = null;
            $resource->save();
        });

        $user->resourceChanges()->each(function ($change) {
            $change->user_id = null;
            $change->save();
        });

        $user->resourceComplaints()->each(function ($complaint) {
            $complaint->user_id = null;
            $complaint->save();
        });

        $user->reviewComplaints()->each(function ($reviewComplaint) {
            $reviewComplaint->user_id = null;
            $reviewComplaint->save();
        });

        $user->reviews()->each(function ($review) {
            $review->complaints()->delete();
            $review->delete();
        });

        $user->votes()->delete();
        $user->providers()->delete();
        $user->supports()->delete();

        ResourceUser::where('user_id', $user->id)->delete();

        $user->delete();
    }
}
