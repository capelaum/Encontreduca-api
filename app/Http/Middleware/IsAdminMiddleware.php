<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IsAdminMiddleware
{
    /**
     * Handle an admin route incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() ||
            !auth()->user()->tokenCan('admin') ||
            !auth()->user()->is_admin
        ) {
            return response()->json([
                'message' => 'Você não tem permissão para acessar essa rota',
            ], 401);
        }

        return $next($request);
    }
}
