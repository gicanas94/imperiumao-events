<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    public function index()
    {
        $title = 'Mensajes';
        $messages = Message::all();

        return view('messages.index', compact('title', 'messages'));
    }

    public function store(Request $request)
    {
        $message = $request->except(['_token']);

        Message::create($message + ['user_id' => auth()->user()->id]);

        $storeSuccess = 'El mensaje ha sido creado exitosamente.';

        return redirect()->route('messages')->with('storeSuccess', $storeSuccess);
    }

    public function edit($id)
    {
        $title = 'Editar mensaje';
        $message = Message::find($id);

        return view('messages.edit', compact('title', 'message'));
    }

    public function update(Request $request, $id)
    {
        $message = Message::find($id);

        $message->title = $request->title;
        $message->content = $request->content;
        $message->class = $request->class;

        $message->save();

        $editSuccess = 'El mensaje ha sido actualizado exitosamente.';

        return redirect()->route('messages')->with('editSuccess', $editSuccess);
    }

    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $message = Message::find($id);

            $message->delete();
        }
    }

    public function state(Request $request, $id)
    {
        if ($request->ajax()) {
            $message = Message::find($id);

            switch ($message->active) {
                case 0:
                    $message->active = 1;
                    $message->save();
                    break;
                case 1:
                    $message->active = 0;
                    $message->save();
                    break;
            }
        }
    }
}
