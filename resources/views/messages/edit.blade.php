@extends('layouts.main')

@section('content')
    <div class="content">
        <div class="close-edit"><a href="{{ route('messages') }}"><span class="fa fa-times"></span></a></div>
        <h2>EDITAR MENSAJE</h2>
        <p>Puedes editar o corregir el mensaje si quieres.</p>
        <hr>
        <form action="{{ route('messages.update', $message->id) }}" method="post">
            {!! csrf_field() !!}
            <div class="message-content">
                <div id="preview" class="green">
                    <p id="titlePreview" class="message-title">{{ $message->title }}</p>
                    <p id="contentPreview" class="message-text">{{ $message->content }}</p>
                    <div class="message-author">
                        <p>{{ date('d/m/Y', strtotime($message->created_at)) . ' - ' . $message->user->username }}</p>
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
                <input id="titleInput" type="text" name="title" value="{{ $message->title }}" required>
            </div>
            <br>
            <div>
                <label><b>*</b>Contenido</label>
                <br>
                <textarea id="contentTextarea" name="content" rows="8" cols="30" required>{{ $message->content }}</textarea>
            </div>
            <input id="selectedClass" type="hidden" name="class" value="{{ $message->class }}">
            <hr>
            <div class="center-content">
                <button class="large-button" type="submit">EDITAR</button>
            </div>
        </form>
    </div>
@endsection
