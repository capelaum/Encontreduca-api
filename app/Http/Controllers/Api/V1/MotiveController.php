<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Motive;
use Illuminate\Http\JsonResponse;

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
