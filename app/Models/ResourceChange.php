<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceChange extends Model
{
    use HasFactory;

    public $timestamps = false;

    static public $fields = [
        'name',
        'address',
        'category_id',
        'categoryId',
        'website',
        'phone',
        'cover',
        'position'
    ];

    protected $fillable = [
        'resource_id',
        'user_id',
        'field',
        'old_value',
        'new_value',
    ];

    protected $with = ['user', 'resource'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
