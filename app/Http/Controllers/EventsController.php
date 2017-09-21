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

        Event::create($event);

        $storeSuccess = 'El evento ha sido creado exitosamente.';

        return redirect()->route('events')->with('storeSuccess', $storeSuccess);
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

        $event->save();

        $editSuccess = 'El evento ha sido actualizado exitosamente.';

        return redirect()->route('events')->with('editSuccess', $editSuccess);
    }

    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $event = Event::find($id);

            $event->delete();
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
                    break;
                case 1:
                    $event->active = 0;
                    $event->save();
                    break;
            }
        }
    }
}
