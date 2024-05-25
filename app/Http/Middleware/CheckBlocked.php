<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = Auth::user();
        $profileUser = $request->route('user'); // Assumes you're passing the user instance to the route

        if ($currentUser && $profileUser && $profileUser->isBlockedBy($currentUser)) {
            return redirect('/blocked');
        }

        return $next($request);
    }
}
