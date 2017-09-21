<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Record;

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
            } else {
                $record->finished = 1;
            }

            if (isset($request->organizes)) {
                $record->organizes = 1;
            } else {
                $record->organizes = 0;
            }

            $record->save();

            if ($record->finished == 1) {
                return response()->json([
                    'message' => 'Evento finalizado, ¡felicitaciones!.'
                ]);
            } elseif ($record->suspended == 1) {
                return response()->json([
                    'message' => 'Evento suspendido.'
                ]);
            }
        }
    }
}
