@extends('layouts.main')

@section('content')
    <div class="content">
        <div class="close-edit"><a href="{{ route('users') }}"><span class="fa fa-times"></span></a></div>
        <h2>EDITAR USUARIO</h2>
        <p>A continuación se encuentra la información del usuario.</p>
        <p>No es obligatorio actualizar su contraseña.</p>
        <hr>
        <form action="{{ route('users.update', $user->id) }}" method="post">
            {!! csrf_field() !!}
            <div class="inline-content float-left-content">
                <div>
                    <label><b>*</b>Nombre de usuario</label>
                    <br>
                    <input class="{{ $errors->has('username') ? 'form-error-content' : '' }}" type="text" name="username" value="{{ old('username', $user->username) }}" required>
                </div>
                <br>
                <div><b>*</b>Correo electrónico</label>
                    <br>
                    <input class="{{ $errors->has('email') ? 'form-error-content' : '' }}" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
                <br>
                <div>
                    <label>Contraseña</label>
                    <br>
                    <input class="{{ $errors->has('password') ? 'form-error-content' : '' }}" type="password" name="password">
                </div>
                <br>
                <div>
                    <label>Confirmar contraseña</label>
                    <br>
                    <input class="{{ $errors->has('password') ? 'form-error-content' : '' }}" type="password" name="password_confirmation">
                </div>
            </div>
            <div class="inline-content float-left-content">
                <div>
                    <label>Poder</label>
                    <br>
                    <select name="power">
                        @for ($i = 0; $i <= auth()->user()->power; $i++)
                            @if ($i == old('power', $user->power))
                                <option value="{{ $i }}" selected>{{ $i }}</option>
                            @else
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>
                </div>
                <div class="small-text">
                    <p>0: usuario sin privilegios</p>
                    <p>1: puede ver registros de eventos</p>
                    <p>2: puede crear, editar, activar/desactivar y eliminar eventos</p>
                    <p>3: puede crear, editar, banear/desbanear y eliminar usuarios</p>
                </div>
                <hr>
                <button class="large-button" type="submit">EDITAR</button>
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
