<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ResourceComplaintCollection;
use App\Http\Resources\Admin\ResourceComplaintResource;
use App\Models\ResourceComplaint;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class ResourceComplaintController extends Controller
{
    /**
     * Returns list of all Resource Complaints.
     *
     * @return ResourceComplaintCollection
     * @throws AuthorizationException
     */
    public function index(): ResourceComplaintCollection
    {
        $this->authorize('isAdmin', [
            ResourceComplaint::class,
            'listar as reclamações de recursos.'
        ]);

        $resourceComplaints = ResourceComplaint::all();

        return new ResourceComplaintCollection($resourceComplaints);
    }

    /**
     * Show single Resource Complaint data.
     *
     * @param ResourceComplaint $complaint
     * @return ResourceComplaintResource
     * @throws AuthorizationException
     */
    public function show(ResourceComplaint $complaint): ResourceComplaintResource
    {
        $this->authorize('isAdmin', [
            ResourceComplaint::class,
            'visualizar essa reclamação de recurso.'
        ]);

        return new ResourceComplaintResource($complaint);
    }
}
