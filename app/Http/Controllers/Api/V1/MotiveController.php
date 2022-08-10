<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\MotiveCollection;
use App\Models\Motive;
use Illuminate\Http\JsonResponse;

class MotiveController extends Controller
{
    /**
     * Returns list of all motives.
     *
     * @return MotiveCollection
     */
    public function index(): MotiveCollection
    {
        return new MotiveCollection(Motive::all());
    }
}
