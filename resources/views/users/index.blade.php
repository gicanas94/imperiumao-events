@extends('layouts.main')

@section('content')
    <div class="table-content">
        <h1>USUARIOS</h1>
        @if (count($users) == 0)
            <p>Actualmente no hay ningún usuario creado...</p>
            <p>Puedes registrar uno si quieres.</p>
        @else
            <p>A continuación se encuentra el listado de los usuarios registrados.</p>
            <p>Debajo, puedes registrar otro.</p>
            <table>
                <thead>
                    <th>NOMBRE</th>
                    <th>PODER</th>
                    <th>ACCIONES</th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->power }}</td>
                            <td>
                                @if (auth()->user()->power >= 1)
                                    <form class="table-form" action="{{ route('users.records', $user->id) }}" method="get">
                                        {!! csrf_field() !!}
                                        <button class="small-button records-button" type="submit">REGISTROS</button>
                                    </form>
                                @endif

                                @if (auth()->user()->power >= 3 && auth()->user()->power > $user->power)
                                    <form class="table-form" action="{{ route('users.edit', $user->id) }}" method="get">
                                        {!! csrf_field() !!}
                                        <button class="small-button edit-button" type="submit">EDITAR</button>
                                    </form>

                                    <form class="table-form" action="{{ route('users.state', $user->id) }}" method="post">
                                        {!! csrf_field() !!}
                                        @if ($user->banned == 0)
                                            <button id="ban" class="small-button ban-button" type="submit">BAN</button>
                                        @elseif ($user->banned == 1)
                                            <button id="unban" class="small-button unban-button" type="submit">UNBAN</button>
                                        @endif
                                    </form>

                                    <form class="table-form" action="{{ route('users.destroy', $user->id) }}" method="post">
                                        {!! csrf_field() !!}
                                        <button class="small-button delete-button" type="submit">ELIMINAR</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if (count($trashedUsers) != 0 && auth()->user()->power > 2)
            <hr>
            <h2>USUARIOS ELIMINADOS</h2>
            <p>A continuación se encuentra el listado de los usuarios eliminados.</p>
            <p>Si estás seguro de lo que haces, puedes restaurar uno.</p>
            <table>
                <thead>
                    <th>NOMBRE</th>
                    <th>ACCIONES</th>
                </thead>
                <tbody>
                    @foreach ($trashedUsers as $trashedUser)
                        <tr>
                            <td>{{ $trashedUser->username }}</td>
                            <td>
                                @if (auth()->user()->power > 0)
                                    <form class="table-form" action="{{ route('users.records', $trashedUser->id) }}" method="get">
                                        {!! csrf_field() !!}
                                        <button class="small-button records-button" type="submit">REGISTROS</button>
                                    </form>
                                @endif

                                @if (auth()->user()->power > 2)
                                    <form class="table-form" action="{{ route('users.restore', $trashedUser->id) }}" method="post">
                                        {!! csrf_field() !!}
                                        <button class="small-button restore-button" type="submit">RESTAURAR</button>
                                    </form>
                                @endif
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

    @if (auth()->user()->power > 2)
        <div class="content">
            <h2>NUEVO USUARIO</h2>
            <p>Completa el siguiente formulario para registrar un nuevo usuario.</p>
            <p>Recuerda que los campos que tengan un <b>*</b> son obligatorios.</p>
            <hr>
            <form action="{{ route('users') }}" method="post">
                {!! csrf_field() !!}
                <div class="inline-content float-left-content">
                    <div>
                        <label><b>*</b>Nombre de usuario</label>
                        <br>
                        <input class="{{ $errors->has('username') ? 'form-error-content' : '' }}" type="text" name="username" value="{{ old('username') }}" required>
                    </div>
                    <br>
                    <div><b>*</b>Correo electrónico</label>
                        <br>
                        <input class="{{ $errors->has('email') ? 'form-error-content' : '' }}" type="email" name="email" value="{{ old('email') }}" required>
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
                    <label>Poder</label>
                    <br>
                    <select name="power">
                        @for ($i = 0; $i <= auth()->user()->power; $i++)
                            @if ($i == old('power'))
                                <option value="{{ $i }}" selected>{{ $i }}</option>
                            @else
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endif
                        @endfor
                    </select>
                    <div class="small-text">
                        <p>0: usuario sin privilegios</p>
                        <p>1: puede ver registros de eventos</p>
                        <p>2: puede crear, editar, activar/desactivar y eliminar eventos</p>
                        <p>3: puede crear, editar, banear/desbanear y eliminar usuarios</p>
                    </div>
                    <hr>
                    <button class="large-button" id="newUser" type="submit">CREAR</button>
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
