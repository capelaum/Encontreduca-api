<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Motive;
use App\Models\Resource;
use App\Models\Review;
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
            'listar os usuários.'
        ]);

        $usersCount = User::count();
        $resourcesCount = Resource::count();
        $reviewsCount = Review::count();
        $categoriesCount = Category::count();
        $motivesCount = Motive::count();
        $supportsCount = Support::count();

        return response()->json([
            'usersCount' => $usersCount,
            'resourcesCount' => $resourcesCount,
            'reviewsCount' => $reviewsCount,
            'categoriesCount' => $categoriesCount,
            'motivesCount' => $motivesCount,
            'supportsCount' => $supportsCount,
        ]);
    }
}
