@extends('layouts.main')

@section('content')
    <div class="content">
        <h1>{{ strtoupper($record->event->name) }}</h1>
        @if ($record->event->description != null)
            <div class="event-description-content">
                <p>{{ $record->event->description }}</p>
            </div>
        @endif
        <hr>
        <p>A continuación se encuentra la información del evento en curso.</p>
        <p>Antes de iniciar con el mismo, asegúrate que no esté por terminar.</p>
        <hr>
        <form action="{{ route('startEvent.start', $record->event->id) }}" method="post">
            {!! csrf_field() !!}
            <h3>INICIO DEL EVENTO...</h3>
            <div class="inline-content float-left-content">
                <div>
                    <label>Servidor</label>
                    <br>
                    <select name="server">
                        @foreach (config('servers') as $id => $name)
                            @if ($record->server == $id)
                                <option value="{{ $id }}" selected>{{ $name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <br>
                <div>
                    <label>Participantes</label>
                    <br>
                    <input type="text" name="participants" value="{{ $record->participants }}">
                </div>
                <br>
                <div>
                    <label>Niveles</label>
                    <br>
                    <input type="text" name="levels" value="{{ $record->levels }}">
                </div>
                <br>
                <div>
                    <label>Inscripción</label>
                    <br>
                    <input type="text" name="inscription" value="{{ $record->inscription }}">
                </div>
            </div>

            <div class="inline-content float-left-content">
                <div>
                    <label>Mapa/s</label>
                    <br>
                    <input type="text" name="maps" value="{{ $record->maps }}">
                </div>
                <br>
                <div>
                    <label><b>*</b>Organizadores</label>
                    <br>
                    <input type="text" name="organizers" value="{{ $record->organizers . ', ' . Auth::user()->username }}" required>
                </div>
                <br>
                <div>
                    <input class="checkbox" type="checkbox" name="drop" {{ $record->drop === 1 ? 'checked' : '' }}>Caída de ítems
                </div>
                <hr>
                <input type="hidden" name="from_record" value="{{ $record->id }}">
                <button class="large-button" type="submit">INICIAR</button>
            </div>
        </form>
    </div>
@endsection
