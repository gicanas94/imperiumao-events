@extends('layouts.main')

@section('content')
    <div class="content">
        <h1>{{ strtoupper($record->event->name) }}</h1>
        <hr>
        <h3>INICIO DEL EVENTO...</h3>
        <div class="inline-content float-left-content">
            <div>
                <label>Servidor</label>
                <br>
                <select name="server" disabled>
                    @foreach (config('servers') as $id => $name)
                        @if ($id == $record->server))
                            <option value="{{ $id }}" selected>{{ $name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <br>
            <div>
                <label>Participantes</label>
                <br>
                <input type="text" name="participants" value="{{ $record->participants }}" disabled>
            </div>
            <br>
            <div>
                <label>Niveles</label>
                <br>
                <input type="text" name="levels" value="{{ $record->levels }}" disabled>
            </div>
        </div>

        <div class="inline-content float-left-content">
            <div>
                <label>Inscripción</label>
                <br>
                <input type="text" name="inscription" value="{{ $record->inscription }}" disabled>
            </div>
            <br>
            <div>
                <label>Mapa/s</label>
                <br>
                <input type="text" name="maps" value="{{ $record->maps }}" disabled>
            </div>
            <br>
            <div>
                <label>Organizadores</label>
                <br>
                <input type="text" name="organizers" value="{{ $record->organizers }}" disabled>
            </div>
            <br>
            <div>
                @if ($record->drop == 1)
                    <input class="checkbox" type="checkbox" name="drop" checked disabled>Caída de ítems
                @else
                    <input class="checkbox" type="checkbox" name="drop" disabled>Caída de ítems
                @endif
            </div>
        </div>

        <p class="small-text">Iniciaste este evento el día <b>{{ date('d/m/Y', strtotime($record->created_at)) }}</b> a las <b>{{ date('G:i', strtotime($record->created_at)) }}</b></p>

        @if (session()->has('startSuccess'))
            <div class="success-content"><p>{{ session('startSuccess') }}</p></div>
        @endif
    </div>

    <div class="content">
        <form id="finishEventForm" action="{{ route('finishEvent.finish', $record->id) }}" method="post">
            {!! csrf_field() !!}
            <h3>FIN DEL EVENTO...</h3>
            <div class="inline-content float-left-content">
                <div>
                    <label><b>*</b>Ganador/es</label>
                    <br>
                    <input id="winnersInput" type="text" name="winners" required>
                </div>
                <br>
                <div>
                    <label><b>*</b>Premio/s</label>
                    <br>
                    <input id="prizesInput" type="text" name="prizes" placeholder="{{ $record->event->gold }}" required>
                </div>
                <br>
                <div>
                    <label>Comentario adicional</label>
                    <br>
                    <textarea id="commentsInput" style="width: 230px" name="comments" rows="4" cols="30"></textarea>
                </div>
            </div>
            <div class="inline-content float-left-content">
                <div>
                    <input id="organizesInput" class="checkbox" type="checkbox" name="organizes">Organizo el evento
                    <p class="small-text">El evento será descontado de tu Stock</p>
                </div>
                <hr>
                <div>
                    <input id="suspendInput" class="checkbox" type="checkbox" name="suspend">Evento suspendido
                </div>
                <br>
                <button id="finishEventButton" class="large-button" type="submit" value="1" name="finish">FINALIZAR</button>
            </div>
        </form>
        <div id="finishEventAlertDiv" class="success-content"><p id="finishEventAlertP"></p></div>
    </div>
@endsection
