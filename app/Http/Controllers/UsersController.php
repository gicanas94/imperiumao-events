<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Record;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\EditUserRequest;

class UsersController extends Controller
{
    protected $recordColors = [
        '#F78181', '#F79F81', '#F7BE81', '#F5DA81', '#F3F781', '#D8F781',
        '#BEF781', '#9FF781', '#81F781', '#81F79F', '#81F7BE', '#81F7D8',
        '#81F7F3', '#81DAF5', '#81BEF7', '#819FF7', '#8181F7', '#9F81F7',
        '#BE81F7', '#DA81F5', '#F781F3', '#F781D8', '#F781BE', '#F7819F'
    ];

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $title = 'Usuarios';
        $users = $this->getUsers();

        return view('users.index', compact('title', 'users'));
    }

    protected function getUsers()
    {
        return User::where('id', '!=', 1)
                        ->orderBy('power', 'desc')
                        ->orderBy('username')
                        ->get();
    }

    public function store(StoreUserRequest $request)
    {
        $user = $request->except(['_token', 'password_confirmation']);

        $user['password'] = bcrypt($request->password);

        try {
            User::create($user);
            $storeSuccess = 'El usuario ha sido creado exitosamente.';
            if (auth()->user()->username != 'admin') {
                $log = '<b>GENERADOR DE EVENTOS:</b> ' . auth()->user()->username . ' creó el usuario ' . "'" . $user['username'] . "'.";
                $this->saveLog($log);
            }
            return redirect()->route('users')->with('storeSuccess', $storeSuccess);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function edit($id)
    {
        $title = 'Editar usuario';
        $user = User::find($id);

        return view('users.edit', compact('title', 'user'));
    }

    public function update(EditUserRequest $request, $id)
    {
        $user = User::find($id);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->power = $request->power;

        if ($request->password != null) {
            $user->password = bcrypt($request->password);
        }

        try {
            $user->save();
            $editSuccess = 'El usuario ha sido actualizado exitosamente.';
            if (auth()->user()->username != 'admin') {
                $log = '<b>GENERADOR DE EVENTOS:</b> ' . auth()->user()->username . ' editó al usuario ' . "'" . $user->username . "'.";
                $this->saveLog($log);
            }
            return redirect()->route('users')->with('editSuccess', $editSuccess);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function destroy(Request $request, $id) {
        if ($request->ajax()) {
            $user = User::find($id);

            try {
                if (auth()->user()->username != 'admin') {
                    $log = '<b>GENERADOR DE EVENTOS:</b> ' . auth()->user()->username . ' eliminó al usuario ' . "'" . $user->username . "'.";
                    $this->saveLog($log);
                }
                $user->delete();
            } catch (Exception $e) {
                return $e;
            }

            $user->delete();
        }
    }

    public function state(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = User::find($id);

            switch ($user->banned) {
                case 0:
                    $user->banned = 1;
                    $user->save();
                    if (auth()->user()->username != 'admin') {
                        $log = '<b>GENERADOR DE EVENTOS:</b> ' . auth()->user()->username . ' bloqueó al usuario ' . "'" . $user->username . "'.";
                        $this->saveLog($log);
                    }
                    break;
                case 1:
                    $user->banned = 0;
                    $user->save();
                    if (auth()->user()->username != 'admin') {
                        $log = '<b>GENERADOR DE EVENTOS:</b> ' . auth()->user()->username . ' desbloqueó al usuario ' . "'" . $user->username . "'.";
                        $this->saveLog($log);
                    }
                    break;
            }
        }
    }

    public function records(Request $request, $id)
    {
        $user = User::find($id);
        $title = 'Registros de ' . $user->username;
        $recordColors = $this->recordColors;
        $months = [1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                   5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                   9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre',
                   12 => 'Diciembre'];

        $years = [2017];

        if ($request->isMethod('post')) {
            $records = $this->getRecords($request, $id);
        }

        return view('users.records', compact('title', 'user', 'recordColors',
                    'months', 'years', 'records'));
    }

    protected function getRecords($request, $id)
    {
        $query = Record::query();

        $query->where('user_id', $id);
        $query->whereMonth('created_at', '=', $request->month);
        $query->whereYear('created_at', '=', $request->year);

        if ($request->input('server') != null) {
            $query->where('server', $request->input('server'));
        }

        $records = $query->get();

        return $records;
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
