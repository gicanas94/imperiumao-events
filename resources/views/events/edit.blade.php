@extends('layouts.main')

@section('content')
    <div class="content">
        <div class="close-edit"><a href="{{ route('events') }}"><span class="fa fa-times"></span></a></div>
        <h2>EDITAR EVENTO</h2>
        <p>A continuación se encuentra la información del evento.</p>
        <p>¡Haz los cambios que quieras!</p>
        <hr>
        <form action="{{ route('events.update', $event->id) }}" method="post">
            {!! csrf_field() !!}
            <div class="inline-content float-left-content">
                <div>
                    <label><b>*</b>Nombre</label>
                    <br>
                    <input class="{{ $errors->has('name') ? 'form-error-content' : '' }}" type="text" name="name" value="{{ old('name', $event->name) }}" required>
                </div>
                <br>
                <div>
                    <label><b>*</b>Niveles</label>
                    <br>
                    <input class="{{ $errors->has('levels') ? 'form-error-content' : '' }}" type="text" name="levels" value="{{ old('levels', $event->levels) }}" required>
                </div>
                <br>
                <div>
                    <label><b>*</b>Inscripción</label>
                    <br>
                    <input class="{{ $errors->has('inscription') ? 'form-error-content' : '' }}" type="text" name="inscription" value="{{ old('inscription', $event->inscription) }}" required>
                </div>
                <br>
                <div>
                    <label><b>*</b>Oro a entregar</label>
                    <br>
                    <input class="{{ $errors->has('gold') ? 'form-error-content' : '' }}" type="text" name="gold" value="{{ old('gold', $event->gold) }}" required>
                </div>
                <br>
                <div>
                    <label><b>*</b>Puede realizarse cada...</label>
                    <br>
                    <input class="{{ $errors->has('stock') ? 'form-error-content' : '' }}" type="text" name="stock" placeholder="días" value="{{ old('stock', $event->stock) }}" required>
                    <p class="small-text">0 días = sin límite de stock</p>
                </div>
            </div>
            <div class="inline-content float-left-content">
                <div>
                    <label>Descripción</label>
                    <br>
                    <textarea class="{{ $errors->has('description') ? 'form-error-content' : '' }}" name="description" rows="9" cols="30">{{ old('description', $event->description) }}</textarea>
                </div>
                <hr>
                <button class="large-button" type="submit">GUARDAR</button>
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
    </div>
@endsection
