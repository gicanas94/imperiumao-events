@extends('layouts.main')

@section('content')
    @if (Auth::check())
        <div class="events-content">
            @if ($defaultPassword == true)
                <div class="message-content">
                    <div class="color red opacity center-content">
                        <p class="message-title">¡No olvides actualizar tu contraseña!</p>
                    </div>
                </div>
            @endif

            @if (count($messages) > 0)
                <hr>
                @foreach ($messages as $message)
                    <div class="message-content">
                        <div class="{{ $message->class }} opacity">
                            <p class="message-title">{{ $message->title }}</p>
                            <p id="contentPreview" class="message-text">{{ $message->content }}</p>
                            <div class="message-author">
                                <p>{{ date('d/m/Y', strtotime($message->created_at)) . ' - ' . $message->user->username }}</p>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 20px"></div>
                @endforeach
            @endif

            @if ($inProgressEvent != null && $notFinishedEvent == null)
                @if ($lastUserRecord != null)
                    @if ($inProgressEvent->id != $lastUserRecord->from_record)
                        <hr>
                        <div class="events">
                            <h2>Eventos en curso...</h2>
                            <div class="event in-progress">
                                <div class="event-server">
                                    @foreach (config('servers') as $id => $name)
                                        @if ($inProgressEvent->server == $id)
                                            {{ $name }}
                                        @endif
                                    @endforeach
                                </div>
                                <a class="event-name" href="{{ route('progress', $inProgressEvent->id) }}"><p>{{ $inProgressEvent->event->name }}</p></a>
                                <div class="event-description">
                                    <p><b>Inicio: </b>{{ date('d/m/Y', strtotime($inProgressEvent->created_at)) }} a las {{ date('G:i', strtotime($inProgressEvent->created_at)) }}</p>
                                    <p style="border-top: none"><b>Organiza: </b>{{ $inProgressEvent->organizers }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($inProgressEvent->id == $lastUserRecord->from_record)
                        <hr>
                        <div class="events">
                            <h2>Eventos en curso...</h2>
                            <div class="event in-progress already-participated">
                                <div class="event-server">
                                    @foreach (config('servers') as $id => $name)
                                        @if ($inProgressEvent->server == $id)
                                            {{ $name }}
                                        @endif
                                    @endforeach
                                </div>
                                <a class="event-name" href=""><p>{{ $inProgressEvent->event->name }}</p></a>
                                <div class="event-description">
                                    <p>Ya participaste de este evento.</p>
                                    <p style="border-top: none"><b>Organizador: </b>{{ $inProgressEvent->user->username }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <hr>
                    <div class="events">
                        <h2>Eventos en curso...</h2>
                        <div class="event in-progress">
                            <div class="event-server">
                                @foreach (config('servers') as $id => $name)
                                    @if ($inProgressEvent->server == $id)
                                        {{ $name }}
                                    @endif
                                @endforeach
                            </div>
                            <a class="event-name" href="{{ route('progress', $inProgressEvent->id) }}"><p>{{ $inProgressEvent->event->name }}</p></a>
                            <div class="event-description">
                                <p><b>Inicio: </b>{{ date('d/m/Y', strtotime($inProgressEvent->created_at)) }} a las {{ date('G:i', strtotime($inProgressEvent->created_at)) }}</p>
                                <p style="border-top: none"><b>Organiza: </b>{{ $inProgressEvent->organizers }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if ($notFinishedEvent != null)
                <hr>
                <div class="events">
                    <h2>Eventos sin finalizar...</h2>
                    <div class="event not-finished">
                        <div class="event-server">
                            @foreach (config('servers') as $id => $name)
                                @if ($notFinishedEvent->server == $id)
                                    {{ $name }}
                                @endif
                            @endforeach
                        </div>
                        <a class="event-name" href="{{ route('finishevent', $notFinishedEvent->id) }}"><p>{{ $notFinishedEvent->event->name }}</p></a>
                        <div class="event-description">
                            <p>Iniciaste este evento el día <b>{{ date('d/m/Y', strtotime($notFinishedEvent->created_at)) }}</b> a las <b>{{ date('G:i', strtotime($notFinishedEvent->created_at)) }}</b></p>
                        </div>
                    </div>
                </div>
            @endif

            @if (count($stockEvents) > 0 && $notFinishedEvent == null && $inProgressEvent == null)
                <hr>
                <div class="events">
                    <h2>Eventos disponibles...</h2>
                    @foreach ($stockEvents as $event)
                        <div class="event stock">
                            <a class="event-name" href="{{ route('startevent', $event->id) }}"><p>{{ $event->name }}</p></a>
                            <div class="event-description">
                                @if ($event->stock != 0)
                                    <p>Puede realizarse cada <b>{{ $event->stock }} días</b></p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (count($noStockEvents) > 0)
                <hr>
                <div class="events">
                    <h2>Eventos no disponibles...</h2>
                    @foreach ($noStockEvents as $event)
                        <div class="event no-stock">
                            <a class="event-name" href=""><p>{{ $event->name }}</p></a>
                            <div class="event-description">
                                <p>Puedes volver realizar este evento el día <b>{{ date('d/m/Y', strtotime($event->availableDate)) }}</b> a las <b>{{ date('G:i', strtotime($event->availableDate)) }}</b></p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (count($notActiveEvents) > 0)
                <hr>
                <div class="events">
                    <h2>Eventos desactivados...</h2>
                    @foreach ($notActiveEvents as $event)
                        <div class="event not-active">
                            <a class="event-name" href=""><p>{{ $event->name }}</p></a>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (count($lastEvents) > 0)
                <hr>
                <div class="events">
                    <h2>Últimos eventos...</h2>
                    @foreach ($lastEvents as $record)
                        <div class="event last">
                            <div class="event-server">
                                @foreach (config('servers') as $id => $name)
                                    @if ($record->server_id == $id)
                                        {{ $name }}
                                    @endif
                                @endforeach
                            </div>
                            <a class="event-name" href=""><p>{{ $record->event->name }}</p></a>
                            <div class="event-description">
                                <p><b>Realizado: </b>{{ date('d/m/Y', strtotime($record->created_at)) }} a las {{ date('G:i', strtotime($record->created_at)) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <hr>
            <p class="center-content">Se {{ count($monthEvents) > 1 ? 'realizaron' : 'realizó' }} <b>{{ count($monthEvents) }}</b> eventos durante el mes.</p>
            <p class="credits">Plataforma desarrollada por <a href="https://github.com/gicanas94">Gabriel Ignacio Cañas</a> para <a href="http://www.imperiumao.com.ar">ImperiumAO</a>.</p>
        </div>
    @endif
@endsection
