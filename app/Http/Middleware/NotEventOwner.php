<?php

namespace App\Http\Middleware;

use Closure;
use App\Record;
use Illuminate\Support\Facades\Auth;

class NotEventOwner
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
        $record = Record::find($request->id);

        if ($record->user_id != auth()->user()->id) {
            return back();
        }
        
        return $next($request);
    }
}
