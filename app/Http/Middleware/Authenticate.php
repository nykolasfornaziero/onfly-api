<?php
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        // For API requests, return null to avoid redirection
        if (!$request->expectsJson()) {
            return null;
        }
    }

    /**
     * Handle unauthenticated requests.
     */
    protected function unauthenticated($request, array $guards)
    {
        // Return a JSON response for unauthenticated API requests
        return response()->json(['error' => 'Unauthenticated'], 401);
    }
}
