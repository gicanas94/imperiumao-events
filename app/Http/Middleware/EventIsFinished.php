<?php

namespace App\Http\Middleware;

use Closure;
use App\Record;
use Illuminate\Support\Facades\Auth;

class EventIsFinished
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

        if ($record->finished == 1 || $record->suspended == 1) {
            return back();
        }

        return $next($request);
    }
}
