<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Record;

class InProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index($id)
    {
        $title = 'Evento en curso';
        $record = Record::find($id);

        return view('progress.index', compact('title', 'record'));
    }
}
