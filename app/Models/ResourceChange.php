<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceChange extends Model
{
    use HasFactory;

    public $timestamps = false;

    static public $fields = [
        'name',
        'address',
        'category_id',
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

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
