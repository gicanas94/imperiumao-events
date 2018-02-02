<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Record;
use App\Event;
use App\User;
use \DB;

class StatsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $title = 'Estadísticas';
        $users = User::all();
        $events = Event::all();

        if ($request->isMethod('post')) {
            $stats = $this->getStats($request);
        }

        return view('stats.index', compact('title', 'users', 'events', 'stats',
                    'request'));
    }

    protected function getStats($request)
    {
        // Eventos más realizados
        $eventStats = DB::table('records')
                            ->join('events', 'records.event_id', '=', 'events.id')
                            ->select('events.name', DB::raw('count(events.id) as count'))
                            ->where('records.finished', 1)
                            ->where('from_record', null)
                            ->whereRaw('MONTH(records.created_at) = ?', $request->month)
                            ->whereRaw('YEAR(records.created_at) = ?', $request->year)
                            ->orderBy('count', 'desc')
                            ->groupBy('events.id')
                            ->get();

        $eventStats = $eventStats->toArray();

        // Usuario que realizó más eventos
        $userStats = DB::table('records')
                            ->join('users', 'records.user_id', '=', 'users.id')
                            ->select('users.username', DB::raw('count(users.id) as count'))
                            ->where('records.finished', 1)
                            ->whereRaw('MONTH(records.created_at) = ?', $request->month)
                            ->whereRaw('YEAR(records.created_at) = ?', $request->year)
                            ->orderBy('count', 'desc')
                            ->groupBy('users.id')
                            ->get();

        $userStats = $userStats->toArray();

        // Eventos por servidor
        $serverStats = DB::table('records')
                            ->select('records.server', DB::raw('count(records.server) as count'))
                            ->where('records.finished', 1)
                            ->where('from_record', null)
                            ->whereRaw('MONTH(records.created_at) = ?', $request->month)
                            ->whereRaw('YEAR(records.created_at) = ?', $request->year)
                            ->groupBy('records.server')
                            ->get();

        $serverStats = $serverStats->toArray();

        //
        return compact('eventStats', 'userStats', 'serverStats');
    }
}
