<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Motive;
use App\Models\Resource;
use App\Models\ResourceComplaint;
use App\Models\ResourceVote;
use App\Models\Review;
use App\Models\ReviewComplaint;
use App\Models\Support;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        $this->authorize('isAdmin', [
            User::class,
            'listar os dados gerais.'
        ]);

        $usersCount = User::count();

        $resourcesCount = Resource::count();
        $approvedResourcesCount = Resource::where('approved', true)->count();
        $notApprovedResourcesCount = Resource::where('approved', false)->count();

        $resourceComplaintsCount = ResourceComplaint::count();

        $resourceVotesCount = ResourceVote::count();
        $approvedResourceVotesCount = ResourceVote::where('vote', true)->count();
        $notApprovedResourceVotesCount = ResourceVote::where('vote', false)->count();

        $reviewsCount = Review::count();
        $reviewComplaintsCount = ReviewComplaint::count();

        $categoriesCount = Category::count();
        $motivesCount = Motive::count();
        $supportsCount = Support::count();


        return response()->json([
            'usersCount' => $usersCount,

            'resourcesCount' => $resourcesCount,
            'approvedResourcesCount' => $approvedResourcesCount,
            'notApprovedResourcesCount' => $notApprovedResourcesCount,

            'resourceComplaintsCount' => $resourceComplaintsCount,

            'resourceVotesCount' => $resourceVotesCount,
            'approvedResourceVotesCount' => $approvedResourceVotesCount,
            'notApprovedResourceVotesCount' => $notApprovedResourceVotesCount,

            'reviewsCount' => $reviewsCount,
            'reviewComplaintsCount' => $reviewComplaintsCount,

            'categoriesCount' => $categoriesCount,
            'motivesCount' => $motivesCount,
            'supportsCount' => $supportsCount,
        ]);
    }
}
