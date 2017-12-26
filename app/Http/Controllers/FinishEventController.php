<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Record;
use App\Event;

class FinishEventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index($id)
    {
        $title = 'Evento sin finalizar';
        $record = Record::find($id);

        return view('finishevent.index', compact('title', 'record'));
    }

    public function finish(Request $request, $id)
    {
        if ($request->ajax()) {
            $record = Record::find($id);

            $record->winners = $request->winners;
            $record->prizes = $request->prizes;

            if (isset($request->suspend)) {
                $record->suspended = 1;
                $stateForLog = 'SUSPENDIDO';
            } else {
                $record->finished = 1;
                $stateForLog = 'FINALIZADO';
            }

            if (empty($request->comments)) {
                $record->comments = '-';
            } else {
                $record->comments = $request->comments;
            }

            if (isset($request->organizes)) {
                $record->organizes = 1;
            } else {
                $record->organizes = 0;
            }

            $eventName = Event::find($record->event_id);
            $eventName = $eventName['name'];

            if (isset($request->organizes)) {
                $organizer = 'sí';
            } else {
                $organizer = 'no';
            }

            try {
                $record->save();

                if (auth()->user()->username != 'admin') {
                    $log = '<b><u>EVENTO ' . $stateForLog . '</u></b><br><br>' .
                    'Evento: ' . $eventName . '<br>' .
                    'Ganador/es: ' . $record->winners . '<br>' .
                    'Premio/s: ' . $record->prizes . '<br>' .
                    'Com. adic.: ' . $record->comments . '<br>' .
                    'Organizador: ' . $organizer;

                    $this->saveLog($log);
                }

                if ($record->finished == 1) {
                return response()->json([
                    'message' => 'Evento finalizado, ¡felicitaciones!.'
                ]);
                } elseif ($record->suspended == 1) {
                    return response()->json([
                        'message' => 'Evento suspendido.'
                    ]);
                }
            } catch (Exception $e) {
                return $e;
            }
        }
    }

    protected function saveLog($log)
    {
        $data = http_build_query(
            array(
                'ek' => env('ek'),
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
