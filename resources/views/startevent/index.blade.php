@extends('layouts.main')

@section('content')
    <div class="content">
        <h1>{{ strtoupper($event->name) }}</h1>
        @if ($event->description != null)
            <div class="event-description-content">
                <p>{{ $event->description }}</p>
            </div>
        @endif
        <hr>
        <p>Completa todos los campos para iniciar el evento.</p>
        <p>Recuerda que los campos que tengan un <b>*</b> son obligatorios.</p>
        <hr>
        <form action="{{ route('startEvent.start', $event->id) }}" method="post">
            {!! csrf_field() !!}
            <h3>INICIO DEL EVENTO...</h3>
            <div class="inline-content float-left-content">
                <div>
                    <label><b>*</b>Servidor</label>
                    <br>
                    <select name="server">
                        @foreach (config('servers') as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <br>
                <div>
                    <label><b>*</b>Participantes</label>
                    <br>
                    <input type="text" name="participants" required>
                </div>
                <br>
                <div>
                    <label><b>*</b>Niveles</label>
                    <br>
                    <input type="text" name="levels" placeholder="{{ $event->levels }}" required>
                </div>
                <br>
                <div>
                    <label><b>*</b>Inscripción</label>
                    <br>
                    <input type="text" name="inscription" placeholder="{{ $event->inscription }}" required>
                </div>
            </div>

            <div class="inline-content float-left-content">
                <div>
                    <label><b>*</b>Mapa/s</label>
                    <br>
                    <input type="text" name="maps" required>
                </div>
                <br>
                <div>
                    <label><b>*</b>Organizadores</label>
                    <br>
                    <input type="text" name="organizers" value="{{ Auth::user()->username }}" required>
                </div>
                <br>
                <div>
                    <input class="checkbox" type="checkbox" name="drop">Caída de ítems
                </div>
                <hr>
                <button class="large-button" type="submit">INICIAR</button>
            </div>
        </form>
    </div>
@endsection
