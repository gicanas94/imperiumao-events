<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class AdminAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::find($request->id);

        if ($user->power == 4) {
            return back();
        }

        return $next($request);
    }
}
