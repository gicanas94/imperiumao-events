@extends('layouts.main')

@section('content')
    <div class="table-content">
        <h1>EVENTOS</h1>
        @if (count($events) == 0)
            <p>Actualmente no hay ningún evento creado...</p>
            <p>¿Qué estás esperando?</p>
        @else
            <p>A continuación se encuentra el listado de todos los eventos, puedes ajustarlos si quieres.</p>
            <p>Además, puedes crear nuevos eventos.</p>
            <table>
                <thead>
                    <th>NOMBRE</th>
                    <th>STOCK</th>
                    <th>ACCIONES</th>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>
                                @if ($event->stock != 0)
                                    {{ $event->stock }} días
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <form class="table-form" action="{{ route('events.edit', $event->id) }}" method="get">
                                    {!! csrf_field() !!}
                                    <button class="small-button edit-button" type="submit">EDITAR</button>
                                </form>

                                <form class="table-form" action="{{ route('events.state', $event->id) }}" method="post">
                                    {!! csrf_field() !!}
                                    @if ($event->active == 1)
                                        <button id="deactivate" class="small-button deactivate-button" type="submit">DESACTIVAR</button>
                                    @elseif ($event->active == 0)
                                        <button id="activate" class="small-button activate-button" type="submit">ACTIVAR</button>
                                    @endif
                                </form>

                                <form class="table-form" action="{{ route('events.destroy', $event->id) }}" method="post">
                                    {!! csrf_field() !!}
                                    <button class="small-button delete-button" type="submit">ELIMINAR</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if (count($trashedEvents) != 0)
            <hr>
            <h2>EVENTOS ELIMINADOS</h2>
            <p>Debajo se encuentra el listado de los eventos eliminados.</p>
            <p>Puedes restaurar uno con un simple <b>click</b>.</p>
            <table>
                <thead>
                    <th>NOMBRE</th>
                    <th>ACCIONES</th>
                </thead>
                <tbody>
                    @foreach ($trashedEvents as $trashedEvent)
                        <tr>
                            <td>{{ $trashedEvent->name }}</td>
                            <td>
                                <form class="table-form" action="{{ route('events.restore', $trashedEvent->id) }}" method="post">
                                    {!! csrf_field() !!}
                                    <button class="small-button restore-button" type="submit">RESTAURAR</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if (session()->has('editSuccess'))
            <div class="success-content"><p>{{ session('editSuccess') }}</p></div>
        @endif
    </div>

    @if (auth()->user()->power > 1)
        <div class="content">
            <h2>NUEVO EVENTO</h2>
            <p>Completa el siguiente formulario para crear un evento.</p>
            <p>Recuerda que los campos que tengan un <b>*</b> son obligatorios.</p>
            <hr>
            <form action="{{ route('events') }}" method="post">
                {!! csrf_field() !!}
                <div class="inline-content float-left-content">
                    <div>
                        <label><b>*</b>Nombre</label>
                        <br>
                        <input class="{{ $errors->has('name') ? 'form-error-content' : '' }}" type="text" name="name" value="{{ old('name') }}" required>
                    </div>
                    <br>
                    <div>
                        <label><b>*</b>Niveles</label>
                        <br>
                        <input class="{{ $errors->has('levels') ? 'form-error-content' : '' }}" type="text" name="levels" value="{{ old('levels') }}" required>
                    </div>
                    <br>
                    <div>
                        <label><b>*</b>Inscripción</label>
                        <br>
                        <input class="{{ $errors->has('inscription') ? 'form-error-content' : '' }}" type="text" name="inscription" value="{{ old('inscription') }}" required>
                    </div>
                    <br>
                    <div>
                        <label><b>*</b>Mapa/s</label>
                        <br>
                        <input class="{{ $errors->has('maps') ? 'form-error-content' : '' }}" type="text" name="maps" value="{{ old('maps') }}" required>
                    </div>
                    <br>
                    <div>
                        <label><b>*</b>Oro a entregar</label>
                        <br>
                        <input class="{{ $errors->has('gold') ? 'form-error-content' : '' }}" type="text" name="gold" value="{{ old('gold') }}" required>
                    </div>
                    <br>
                    <div>
                        <label><b>*</b>Puede realizarse cada...</label>
                        <br>
                        <input class="{{ $errors->has('stock') ? 'form-error-content' : '' }}" type="text" name="stock" placeholder="días" value="{{ old('stock') }}" required>
                        <p class="small-text">0 días = sin límite de stock</p>
                    </div>
                </div>
                <div class="inline-content float-left-content">
                    <div>
                        <label>Descripción</label>
                        <br>
                        <textarea class="{{ $errors->has('description') ? 'form-error-content' : '' }}" name="description" rows="12" cols="30">{{ old('description') }}</textarea>
                    </div>
                    <hr>
                    <button class="large-button" type="submit">CREAR</button>
                </div>
            </form>

            @if ($errors->any())
                <div class="error-content">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session()->has('storeSuccess'))
                <div class="success-content"><p>{{ session('storeSuccess') }}</p></div>
            @endif
        </div>
    @endif
@endsection
