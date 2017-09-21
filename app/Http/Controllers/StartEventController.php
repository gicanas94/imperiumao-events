<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Record;

class StartEventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index($id)
    {
        $title = 'Nuevo evento';
        $event = $this->getEvent($id);

        return view('startevent.index', compact('title', 'event'));
    }

    protected function getEvent($id)
    {
        return Event::find($id);
    }

    public function start(Request $request, $id)
    {
        $event = Event::find($id);

        if (isset($request->drop)) {
            $request->drop = 1;
        } else {
            $request->drop = 0;
        }

        $record = Record::create([
            'event_id' => $event->id,
            'user_id' => auth()->user()->id,
            'from_record' => $request->from_record,
            'server' => $request->input('server'),
            'participants' => $request->participants,
            'drop' => $request->drop,
            'levels' => $request->levels,
            'inscription' => $request->inscription,
            'maps' => $request->maps,
            'organizers' => $request->organizers,
        ]);

        $startSuccess = 'Evento iniciado. Cuando este termine, no olvides completar el resto del formulario.';

        return redirect()->route('finishevent', $record->id)->with('startSuccess', $startSuccess);
    }
}
