<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Record;
use App\Message;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    protected $stockEvents = [];
    protected $noStockEvents = [];

    public function index()
    {
        $title = 'Inicio';

        $this->getEvents();

        $stockEvents = $this->stockEvents;
        $noStockEvents = $this->noStockEvents;
        $notActiveEvents = $this->getNotActiveEvents();
        $notFinishedEvents = $this->getNotFinishedEvents();
        $inProgressEvents = $this->getInProgressEvents();
        $lastEvents = $this->getLastEvents();
        $messages = $this->getMessages();
        $defaultPassword = $this->getDefaultPassword();

        return view('index', compact('title', 'stockEvents', 'noStockEvents',
                                    'notActiveEvents', 'notFinishedEvents',
                                    'inProgressEvents', 'lastEvents', 'messages',
                                    'defaultPassword'));
    }

    protected function getEvents()
    {
        $events = Event::where('active', 1)
                            ->orderBy('name')
                            ->get();

        foreach ($events as $event) {
            $lastRecord = Record::where('user_id', auth()->user()->id)
                                    ->where('event_id', $event->id)
                                    ->where('finished', 1)
                                    ->where('organizes', 1)
                                    ->orderBy('created_at', 'desc')
                                    ->first();

            if (isset($lastRecord->created_at) && $event->stock != 0) {
                $recordDate = Carbon::parse($lastRecord->created_at);
                $actualDate = Carbon::now(-3);

                $diff = $recordDate->diffInDays($actualDate);

                if ($diff <= $event->stock) {
                    $event->availableDate = $recordDate->addDays($event->stock);
                    $this->noStockEvents[] = $event;
                } else {
                    $this->stockEvents[] = $event;
                }
            } else {
                $this->stockEvents[] = $event;
            }
        }
    }

    protected function getNotActiveEvents()
    {
        $events = Event::where('active', 0)
                            ->orderBy('name')
                            ->get();

        return $events;
    }

    protected function getNotFinishedEvents()
    {
        $events = Record::where('user_id', auth()->user()->id)
                            ->where('finished', null)
                            ->where('suspended', null)
                            ->get();

        return $events;
    }

    protected function getInProgressEvents()
    {
        $events = [];

        $lastRecords = Record::where('finished', null)
                                ->where('suspended', null)
                                ->where('user_id', '!=', auth()->user()->id)
                                ->where('from_record', null)
                                ->get();

        $lastUserRecords = Record::where('user_id', auth()->user()->id)
                                    ->where('from_record', '!=', null)
                                    ->get();

        if (count($lastRecords) > 0) {
            foreach ($lastRecords as $lastRecord) {
                if (count($lastUserRecords) > 0) {
                    foreach ($lastUserRecords as $lastUserRecord) {
                        if ($lastRecord->id != $lastUserRecord->from_record) {
                            $events[] = $lastRecord;
                        }
                    }
                } else {
                    $events[] = $lastRecord;
                }
            }
        }

        return $events;
    }

    protected function getLastEvents()
    {
        return Record::where('finished', 1)
                        ->where('from_record', null)
                        ->orderBy('created_at', 'desc')
                        ->limit(8)
                        ->get();
    }

    protected function getMessages()
    {
        return Message::where('active', 1)
                        ->orderBy('created_at')
                        ->get();
    }

    protected function getDefaultPassword()
    {
        if (Hash::check('123456', auth()->user()->password)) {
            return $defaultPassword;
        }
    }
}
