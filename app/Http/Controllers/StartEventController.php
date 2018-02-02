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

        try {
            $record = Record::create([
                'event_id' => $event->id,
                'user_id' => auth()->user()->id,
                'from_record' => $request->from_record,
                'server_id' => $request->input('server'),
                'participants' => $request->participants,
                'drop' => $request->drop,
                'levels' => $request->levels,
                'inscription' => $request->inscription,
                'maps' => $request->maps,
                'organizers' => $request->organizers,
            ]);

            $startSuccess = 'Evento iniciado. Cuando este termine, no olvides completar el resto del formulario.';

            $eventName = Event::find($event->id);
            $eventName = $eventName['name'];

            foreach (config('servers') as $id => $name) {
                if ($record->server_id == $id) {
                    $serverName = $name;
                }
            }

            if ($request->drop == 0) {
                $drop = 'no';
            } else {
                $drop = 'sí';
            }

            if (auth()->user()->username != 'admin') {
                $log = '<b><u>EVENTO INICIADO</u></b><br><br>' .
                'Evento: ' . $eventName . '<br>' .
                'Servidor: ' . $serverName . '<br>' .
                'Participantes: ' . $request->participants . '<br>' .
                'Niveles: ' . $request->levels . '<br>' .
                'Inscripción: ' . $request->inscription . '<br>' .
                'Mapa/s: ' . $request->maps . '<br>' .
                'Organizadores: ' . $request->organizers . '<br>' .
                'Caída de ítems: ' . $drop;

                $this->saveLog($log);
            }

            return redirect()->route('finishevent', $record->id)->with('startSuccess', $startSuccess);
        } catch (Exception $e) {
            return $e;
        }
    }

    protected function saveLog($log)
    {
        $data = http_build_query(
            array(
                'ek' => config('ek'),
                'nick' => auth()->user()->username,
                'log' => $log
            ));

        $url = 'https://www.imperiumao.com.ar/ext/eventsapp.php?' . $data;

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            dd($e);
        }
    }
}
