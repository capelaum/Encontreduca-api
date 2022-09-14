<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\MotiveCollection;
use App\Http\Resources\Admin\MotiveResource;
use App\Models\Motive;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MotiveController extends Controller
{
    /**
     * Returns list of all motives.
     *
     * @return MotiveCollection
     * @throws AuthorizationException
     */
    public function index(): MotiveCollection
    {
        $this->authorize('isAdmin', [
            Motive::class,
            'visualizar os motivos.'
        ]);

        return new MotiveCollection(Motive::all());
    }

    /**
     * @param Motive $motive
     * @return MotiveResource
     * @throws AuthorizationException
     */
    public function show(Motive $motive): MotiveResource
    {
        $this->authorize('isAdmin', [
            Motive::class,
            'visualizar esse motivo.'
        ]);

        return new MotiveResource($motive);
    }

    /**
     * @param Request $request
     * @return MotiveResource
     * @throws AuthorizationException
     */
    public function store(Request $request): MotiveResource
    {
        $this->authorize('isAdmin', [
            Motive::class,
            'criar motivo.'
        ]);

        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string|in:resource_complaint,review_complaint',
        ]);

        $motive = Motive::create($data);
        return new MotiveResource($motive);
    }

    /**
     * @param Request $request
     * @param Motive $motive
     * @return MotiveResource
     * @throws AuthorizationException
     */
    public function update(Request $request, Motive $motive): MotiveResource
    {
        $this->authorize('isAdmin', [
            Motive::class,
            'editar esse motivo.'
        ]);

        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string|in:resource_complaint,review_complaint',
        ]);

        $motive->update($data);
        return new MotiveResource($motive);
    }

    /**
     * @param Motive $motive
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Motive $motive): JsonResponse
    {
        $this->authorize('isAdmin', [
            Motive::class,
            'deletar esse motivo.'
        ]);

        $motive->delete();
        return response()->json(null, 204);
    }
}
