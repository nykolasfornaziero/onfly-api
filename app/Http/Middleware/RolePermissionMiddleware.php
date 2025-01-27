<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolePermissionMiddleware
{
    /**
     * Handle an incoming request.
     */

    public function handle(Request $request, Closure $next, ...$roleOrPermission)
    {
        $user = auth()->user();

        // Verifica se o usuário tem a role ou permissão necessária
        if (!$user || !$user->hasAnyRole($roleOrPermission) && !$user->hasAnyPermission($roleOrPermission)) {
            return response()->json(['error' => 'Você não tem permissão para acessar este recurso.'], 403);
        }

        return $next($request);
    }
}
