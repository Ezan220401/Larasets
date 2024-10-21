<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $allowedGroupIds = [1, 2, 3, 4, 5, 6];
        if (auth()->guest() || !in_array(auth()->user()->group_id, $allowedGroupIds)) {
            abort(403);
        }

        // Tambahkan pengecekan khusus untuk /asset/create
        if ($request->is('asset/create') && !in_array(auth()->user()->group_id, [1, 2, 3, 4, 5, 6])) {
            abort(403);
        }

        return $next($request);
    }
}

Gate::define('is_admin', function ($user) {
    return in_array($user->group_id, [1, 2, 3, 4, 5, 6]);
});