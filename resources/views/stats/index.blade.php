@extends('layouts.main')

@section('content')
    <div class="content">
        <h1>ESTADÍSTICAS</h1>
        <p>Puedes generar las estadísticas que desees seleccionando mes y año.</p>
        <div class="message-content">
            <div class="color red opacity center-content">
                <p class="message-title">Este sitio se encuentra en construcción, pero operativo.</p>
            </div>
        </div>
        <hr>
        <form action="{{ route('stats') }}" method="post">
            {!! csrf_field() !!}
            <div class="center-content">
                <label>Mes:</label>
                <select class="stats-select" name="month" required>
                    <option value="">-</option>
                    @foreach (config('months') as $number => $name)
                        <option value="{{ $number }}">{{ $name }}</option>
                    @endforeach
                </select>
                <label>Año:</label>
                <select class="stats-select" name="year" required>
                    <option value="">-</option>
                    @foreach (config('years') as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <button class="medium-button" type="submit">GENERAR</button>
            </div>
        </form>
    </div>

    @if ($request->isMethod('post') && empty(array_filter($stats)))
        <div class="content">
            <div class="no-stats-content">
                <p>No se realizó ningún evento durante el periodo seleccionado.</p>
            </div>
        </div>
    @endif

    @if ($request->isMethod('post') && ! empty(array_filter($stats)))
        <div class="table-content">
            @foreach (config('months') as $number => $name)
                @if ($number == $request->month)
                    <h1>ESTADÍSTICAS DE <u>{{ strtoupper($name) }}</u></h1>
                @endif
            @endforeach
            <hr>
            <p>Eventos más realizados...</p>
            <table>
                <thead>
                    <th>EVENTO</th>
                    <th>CANTIDAD</th>
                </thead>
                <tbody>
                    @foreach ($stats['eventStats'] as $stat)
                        <tr>
                            <td>{{ $stat->name }}</td>
                            <td>{{ $stat->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>
            <p>Usuarios ordenados por cantidad de eventos...</p>
            <table>
                <thead>
                    <th>NOMBRE</th>
                    <th>CANTIDAD</th>
                </thead>
                <tbody>
                    @foreach ($stats['userStats'] as $stat)
                        <tr>
                            <td>{{ $stat->username }}</td>
                            <td>{{ $stat->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>
            <p>Eventos por servidor...</p>
            <table>
                <thead>
                    <th>SERVIDOR</th>
                    <th>CANTIDAD</th>
                </thead>
                <tbody>
                    @foreach (config('servers') as $id => $name)
                        <tr>
                            <td>{{ $name }}</td>
                            <td>
                                @foreach ($stats['serverStats'] as $stat)
                                    @if ($stat->server_id == $id)
                                        {{ 0 + $stat->count }}
                                    @endif
                                @endforeach

                                @if (! array_key_exists($id - 1, $stats['serverStats']))
                                    {{ 0 }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
