<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\Record;
use App\Message;
use Carbon\Carbon;
Use Hash;

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
        $notFinishedEvent = $this->getNotFinishedEvent();
        $inProgressEvent = $this->getInProgressEvent();
        $lastUserRecord = $this->getLastUserRecord();
        $lastEvents = $this->getLastEvents();
        $messages = $this->getMessages();
        $defaultPassword = $this->getDefaultPassword();
        $monthEvents = $this->getMonthEvents();

        return view('index', compact('title', 'stockEvents', 'noStockEvents',
                                    'notActiveEvents', 'notFinishedEvent',
                                    'inProgressEvent','lastEvents', 'lastUserRecord',
                                    'messages', 'defaultPassword', 'monthEvents'));
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
        return Event::where('active', 0)
                            ->orderBy('name')
                            ->get();
    }

    protected function getNotFinishedEvent()
    {
        return Record::where('user_id', auth()->user()->id)
                            ->where('finished', null)
                            ->where('suspended', null)
                            ->orderBy('id', 'desc')
                            ->first();
    }

    protected function getInProgressEvent()
    {
        return Record::where('finished', null)
                                ->where('suspended', null)
                                ->where('user_id', '!=', auth()->user()->id)
                                ->where('from_record', null)
                                ->orderBy('id', 'desc')
                                ->first();
    }

    protected function getLastUserRecord()
    {
        return Record::where('user_id', auth()->user()->id)
                                    ->where('from_record', '!=', null)
                                    ->orderBy('id', 'desc')
                                    ->first();
    }

    protected function getLastEvents()
    {
        return Record::where('finished', 1)
                        ->where('from_record', null)
                        ->orderBy('created_at', 'desc')
                        ->limit(16)
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
            return $this->defaultPassword = true;
        }
    }

    protected function getMonthEvents()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');

        return Record::whereRaw('MONTH(created_at) = ?', $currentMonth)
            ->whereRaw('YEAR(created_at) = ?', $currentYear)
            ->where('from_record', null)
            ->where('finished', 1)
            ->get();
    }
}
