@extends('layouts.main')

@section('content')
    <div class="content">
        <h1>MI CUENTA</h1>
        <p>Esta es la información de tu cuenta. Por el momento, solo puedes actualizar tu contraseña.</p>
        <hr>
        <form id="editAccount" action="{{ route('account.update') }}" method="post">
            {!! csrf_field() !!}
            <div class="inline-content float-left-content">
                <div>
                    <label>Nombre de usuario</label>
                    <br>
                    <input type="text" value="{{ auth()->user()->username }}" disabled>
                </div>
                <br>
                <div>
                    <label>Correo electrónico</label>
                    <br>
                    <input type="text" value="{{ auth()->user()->email }}" disabled>
                </div>
                <br>
                <div>
                    <label><b>*</b>Contraseña</label>
                    <br>
                    <input class="{{ $errors->has('password') ? 'form-error-content' : '' }}" type="password" name="password" required>
                </div>
                <br>
                <div>
                    <label><b>*</b>Confirmar contraseña</label>
                    <br>
                    <input class="{{ $errors->has('password') ? 'form-error-content' : '' }}" type="password" name="password_confirmation" required>
                </div>
            </div>
            <div class="inline-content float-left-content">
                <div>
                    <label><b>*</b>Contraseña actual</label>
                    <br>
                    <input class="{{ $errors->has('current_password') ? 'form-error-content' : '' }}" type="password" name="current_password" required>
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

        @if (session()->has('editSuccess'))
            <div class="success-content"><p>{{ session('editSuccess') }}</p></div>
        @endif
    </div>
@endsection
