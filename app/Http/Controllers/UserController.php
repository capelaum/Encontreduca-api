<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->resource_count = $user->resources()->count();
            $user->review_count = $user->reviews()->count();
        }

        return response()->json($users);
    }

    public function show(User $user)
    {
        $user->load('resources');
        $user->resource_count = $user->resources()->count();
        $user->review_count = $user->reviews()->count();

        return response()->json($user);
    }
}
