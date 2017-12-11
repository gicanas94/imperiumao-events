@extends('layouts.main')

@section('content')
    <div class="content">
        <h2>REGISTROS DE <u>{{ strtoupper($user->username) }}</u></h2>
        <p>A continuación, puedes filtrar por mes, año y servidor para obtener
            todos los registros de los eventos que realizó {{ $user->username }}.
        </p>
        <hr>
        <form action="{{ route('users.records', $user->id) }}" method="post">
            {!! csrf_field() !!}
            <div class="center-content">
                <label>Mes:</label>
                <select class="records-select" name="month" required>
                    <option value="">-</option>
                    @foreach ($months as $number => $name)
                        <option value="{{ $number }}">{{ $name }}</option>
                    @endforeach
                </select>
                <label>Año:</label>
                <select class="records-select" name="year" required>
                    <option value="">-</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <label>Servidor:</label>
                <select class="records-select" name="server">
                    <option value="">-</option>
                    @foreach (config('servers') as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <hr>
            <div class="center-content">
                <button class="large-button" type="submit">FILTRAR</button>
            </div>
        </form>
    </div>

    @if (isset($records))
        <div class="content">
            @if (count($records) == 0)
                <div class="no-records-content">
                    <p>No hay registros durante el periodo seleccionado en el servidor seleccionado.</p>
                </div>
            @else
                <p class="small-text">Total de eventos: {{ count($records) }}</p>
                @foreach ($records as $record)
                    <div class="record-content" style="background-color: {{ $recordColors[rand(1, count($recordColors) - 1)] }}">
                        <div class="center-content">
                            <h3><u>{{ strtoupper($record->event->name) }}</u></h3>
                        </div>
                        <div class="inline-content float-left-content">
                            <p><b>Servidor: </b>
                                @foreach (config('servers') as $id => $name)
                                    @if ($record->server == $id)
                                        {{ $name }}
                                    @endif
                                @endforeach
                            </p>
                            <p><b>Participantes: </b>{{ $record->participants }}</p>
                            <p><b>Caída de ítems: </b>
                                @if ($record->drop == 1)
                                    sí
                                @else
                                    no
                                @endif
                            </p>
                            <p><b>Niveles: </b>{{ $record->levels }}</p>
                            <p><b>Inscripción: </b>{{ $record->inscription }}</p>
                            <p><b>Mapa/s: </b>{{ $record->maps }}</p>
                            <p><b>Organizadores: </b>{{ $record->organizers }}</p>
                            <p><b>Organiza: </b>
                                @if ($record->organizes == 1)
                                    sí
                                @else
                                    no
                                @endif
                            </p>
                        </div>

                        <div class="inline-content float-left-content">
                            <p><b>Ganador/es: </b>{{ $record->winners }}</p>
                            <p><b>Premio/s: </b>{{ $record->prizes }}</p>
                            <p><b>Comentarios: </b>{{ $record->comments }}</p>
                            <p><b>Estado: </b>
                                @if ($record->finished == 1)
                                    finalizado
                                @elseif ($record->suspended == 1)
                                    <u>suspendido</u>
                                @endif
                            </p>
                            <p><b>Inicio: </b>{{ date('G:i:s', strtotime($record->created_at)) }} - {{ date('d/m/Y', strtotime($record->created_at)) }}</p>
                            <p><b>Fin: </b>{{ date('G:i:s', strtotime($record->updated_at)) }} - {{ date('d/m/Y', strtotime($record->updated_at)) }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @endif
@endsection
