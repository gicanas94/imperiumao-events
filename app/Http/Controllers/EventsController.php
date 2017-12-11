<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\EditEventRequest;

class EventsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $title = 'Eventos';
        $events = $this->getEvents();

        return view('events.index', compact('title', 'events'));
    }

    protected function getEvents()
    {
        return Event::orderBy('name')->get();
    }

    public function store(StoreEventRequest $request)
    {
        $event = $request->except(['_token']);

        try {
            Event::create($event);
            $storeSuccess = 'El evento ha sido creado exitosamente.';
            if (auth()->user()->username != 'admin') {
                $log = '<b>GENERADOR DE EVENTOS: ' . auth()->user()->username . ' creó el evento ' . "'" . $event['name'] . "'.</b>";
                $this->saveLog($log);
            }
            return redirect()->route('events')->with('storeSuccess', $storeSuccess);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function edit($id)
    {
        $title = 'Editar evento';
        $event = Event::find($id);

        return view('events.edit', compact('title', 'event'));
    }

    public function update(EditEventRequest $request, $id)
    {
        $event = Event::find($id);

        $event->name = $request->name;
        $event->description = $request->description;
        $event->levels = $request->levels;
        $event->inscription = $request->inscription;
        $event->gold = $request->gold;
        $event->stock = $request->stock;

        try {
            $event->save();
            $editSuccess = 'El evento ha sido actualizado exitosamente.';
            if (auth()->user()->username != 'admin') {
                $log = '<b>GENERADOR DE EVENTOS: ' . auth()->user()->username . ' editó el evento ' . "'" . $event['name'] . "'.</b>";
                $this->saveLog($log);
            }
            return redirect()->route('events')->with('editSuccess', $editSuccess);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $event = Event::find($id);

            try {
                if (auth()->user()->username != 'admin') {
                    $log = '<b>GENERADOR DE EVENTOS: ' . auth()->user()->username . ' eliminó el evento ' . "'" . $event['name'] . "'.</b>";
                    $this->saveLog($log);
                }
                $event->delete();
            } catch (Exception $e) {
                return $e;
            }
        }
    }

    public function state(Request $request, $id)
    {
        if ($request->ajax()) {
            $event = Event::find($id);

            switch ($event->active) {
                case 0:
                    $event->active = 1;
                    $event->save();
                    if (auth()->user()->username != 'admin') {
                        $log = '<b>GENERADOR DE EVENTOS: ' . auth()->user()->username . ' activó el evento ' . "'" . $event['name'] . "'.</b>";
                        $this->saveLog($log);
                    }
                    break;
                case 1:
                    $event->active = 0;
                    $event->save();
                    if (auth()->user()->username != 'admin') {
                        $log = '<b>GENERADOR DE EVENTOS: ' . auth()->user()->username . ' desactivó el evento ' . "'" . $event['name'] . "'.</b>";
                        $this->saveLog($log);
                    }
                    break;
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

        $url = 'https://inbound.imperiumao.com.ar/ext/eventsapp.php?' . $data;

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
