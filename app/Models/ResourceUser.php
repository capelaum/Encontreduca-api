<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceUser extends Model
{
    use HasFactory;

    protected $table = 'resource_user';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'resource_id',
    ];
}
