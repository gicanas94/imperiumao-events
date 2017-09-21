@extends('layouts.main')

@section('content')
    <div class="table-content">
        <h1>MENSAJES</h1>
        @if (count($messages) == 0)
            <p>Por el momento, ningún mensaje fue creado.</p>
            <p>De ser necesario, puedes crear uno.</p>
        @else
            <p>A continuación se encuentra el listado de todos los mensajes creados, ordenados por fecha de creación.</p>
            <p>Debajo, puedes crear otro.</p>
            <table>
                <thead>
                    <th>FECHA</th>
                    <th>TÍTULO</th>
                    <th>ACCIONES</th>
                </thead>
                <tbody>
                    @foreach ($messages as $message)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($message->created_at)) }}</td>
                            <td class="message-td">{{ $message->title }}</td>

                            <td>
                                <form class="table-form" action="{{ route('messages.edit', $message->id) }}" method="get">
                                    {!! csrf_field() !!}
                                    <button class="small-button edit-button" type="submit">EDITAR</button>
                                </form>

                                <form class="table-form" action="{{ route('messages.state', $message->id) }}" method="post">
                                    {!! csrf_field() !!}
                                    @if ($message->active == 1)
                                        <button id="deactivate" class="small-button deactivate-button" type="submit">DESACTIVAR</button>
                                    @elseif ($message->active == 0)
                                        <button id="activate" class="small-button activate-button" type="submit">ACTIVAR</button>
                                    @endif
                                </form>

                                <form class="table-form" action="{{ route('messages.destroy', $message->id) }}" method="post">
                                    {!! csrf_field() !!}
                                    <button class="small-button delete-button" type="submit">ELIMINAR</button>
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

    <div class="content">
        <h2>NUEVO MENSAJE</h2>
        <p>Completa el siguiente formulario para crear un nuevo mensaje.</p>
        <hr>
        <form action="{{ route('messages') }}" method="post">
            {!! csrf_field() !!}
            <div class="message-content">
                <div id="preview" class="green">
                    <p id="titlePreview" class="message-title">Título del mensaje</p>
                    <p id="contentPreview" class="message-text">Contenido del mensaje...</p>
                    <div class="message-author">
                        <p>{{ date('d/m/Y') . ' - ' . auth()->user()->username }}</p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="center-content colors-content">
                <div class="color green active"></div>
                <div class="color red"></div>
                <div class="color orange"></div>
                <div class="color blue"></div>
            </div>
            <div>
                <label><b>*</b>Título</label>
                <br>
                <input id="titleInput" type="text" name="title" required>
            </div>
            <br>
            <div>
                <label><b>*</b>Contenido</label>
                <br>
                <textarea id="contentTextarea" name="content" rows="8" cols="30" required></textarea>
            </div>
            <input id="selectedClass" type="hidden" name="class" value="color green">
            <hr>
            <div class="center-content">
                <button class="large-button" type="submit">CREAR</button>
            </div>
        </form>
    </div>
@endsection
