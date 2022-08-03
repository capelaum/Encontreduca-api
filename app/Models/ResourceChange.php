<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceChange extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'resource_id',
        'user_id',
        'field',
        'old_value',
        'new_value',
    ];
}
