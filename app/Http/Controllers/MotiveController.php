<?php

namespace App\Http\Controllers;

use App\Models\Motive;
use Illuminate\Http\Request;

class MotiveController extends Controller
{
    public function index()
    {
        $motives = Motive::all();

        return response()->json($motives);
    }
}
