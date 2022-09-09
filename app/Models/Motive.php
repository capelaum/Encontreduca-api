<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motive extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    public function resourceComplaints(): HasMany
    {
        return $this->hasMany(ResourceComplaint::class);
    }

    public function reviewComplaints(): HasMany
    {
        return $this->hasMany(ReviewComplaint::class);
    }
}
