<?php

namespace App\Http\Controllers;

use App\Models\Motive;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MotiveController extends Controller
{
    /**
     * Returns list of all motives.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $motives = Motive::all();

        return response()->json($motives);
    }
}
