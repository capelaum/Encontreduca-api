<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ResourceComplaintCollection;
use App\Http\Resources\Admin\ResourceComplaintResource;
use App\Models\ResourceComplaint;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ResourceComplaintController extends Controller
{
    /**
     * Returns list of all Resource Complaints.
     *
     * @param Request $request
     * @return ResourceComplaintCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): ResourceComplaintCollection
    {
        $this->authorize('isAdmin', [
            ResourceComplaint::class,
            'listar as reclamações de recursos.'
        ]);

        $resourceComplaints = ResourceComplaint::query();

        $resourceComplaints
            ->when($request->search, function ($query, $search) {
                return $query
                    ->whereHas('resource', function ($query) use ($search) {
                        return $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($query) use ($search) {
                        return $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });

        $resourceComplaints = $resourceComplaints->paginate(20);

        return new ResourceComplaintCollection($resourceComplaints);
    }

    /**
     * Show single Resource Complaint data.
     *
     * @param ResourceComplaint $complaint
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public
    function show(
        ResourceComplaint $complaint
    ): JsonResponse {
        $this->authorize('isAdmin', [
            ResourceComplaint::class,
            'visualizar essa reclamação de recurso.'
        ]);

        return response()->json(new ResourceComplaintResource($complaint));
    }

    /**
     * Delete a Resource Complaint.
     *
     * @param ResourceComplaint $complaint
     * @return Response
     * @throws AuthorizationException
     */
    public
    function destroy(
        ResourceComplaint $complaint
    ): Response {
        $this->authorize('isAdmin', [
            ResourceComplaint::class,
            'excluir essa reclamação de recurso.'
        ]);

        $complaint->delete();

        return response()->noContent();
    }
}
