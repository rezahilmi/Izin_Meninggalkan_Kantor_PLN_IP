<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAkses
{
    public function handle(Request $request, Closure $next, ...$role)
    {
        $user = auth()->user();

        if (in_array($user->role, $role)) {
            return $next($request);
        }

        return redirect('/');
    }
}
