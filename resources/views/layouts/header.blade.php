<header>
    <nav>
        <ul>
            @if (auth()->check())
                <li><a href="{{ route('index') }}">INICIO</a></li>

                @if (auth()->user()->power > 1)
                    <li><a href="{{ route('events') }}">EVENTOS</a></li>
                @endif

                @if (auth()->user()->power > 0)
                    <li><a href="{{ route('users') }}">USUARIOS</a></li>
                @endif

                @if (auth()->user()->power > 2)
                    <li><a href="{{ route('messages') }}">MENSAJES</a></li>
                @endif

                <li><a href="{{ route('account') }}">MI CUENTA</a></li>

                <li><a href="{{ route('logout') }}">SALIR</a></li>
            @endif
        </ul>
    </nav>
</header>
