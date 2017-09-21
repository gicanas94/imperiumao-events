<?php

namespace App\Http\Middleware;

use Closure;
use App\Event;

class EventIsDeactivated
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
        $event = Event::find($request->id);

        if ($event->active == 0) {
            return back();
        }

        return $next($request);
    }
}
