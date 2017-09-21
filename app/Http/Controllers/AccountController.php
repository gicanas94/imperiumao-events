<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\EditAccountRequest;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = 'Mi cuenta';

        return view('account.index', compact('title'));
    }

    public function update(EditAccountRequest $request)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if ($request->password != null) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        $editSuccess = 'La información ha sido actualizado exitosamente.';

        return redirect()->route('account')->with('editSuccess', $editSuccess);
    }
}
