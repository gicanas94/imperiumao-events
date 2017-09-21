@extends('layouts.main')

@section('content')
    <div class="content">
        <h1>Eventos ImperiumAO</h1>
        <div class="center-content">
            <p>Ingresa tus datos de acceso para continuar.</p>
            <form method="post" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div>
                    <label>Nombre de usuario</label>
                    <br>
                    <input class="{{ $errors->has('username') ? 'form-error-content center-content' : 'center-content' }}" type="text" name="username">
                </div>
                <br>
                <div>
                    <label>Contraseña</label>
                    <br>
                    <input class="{{ $errors->has('password') ? 'form-error-content center-content' : 'center-content' }}" type="password" name="password">
                </div>
                <br>
                <div>
                    <input class="checkbox" type="checkbox" name="remember"><label>Recordar ingreso</label>
                </div>
                <br>
                <button class="large-button" type="submit" name="button">LOGIN</button>
            </form>
        </div>

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
