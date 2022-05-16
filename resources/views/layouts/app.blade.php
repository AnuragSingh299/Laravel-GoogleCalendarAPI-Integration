<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Calendar Inc.</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* body {
            background: white;
        } */
        /* table.home-events-table th{
            border: none;
        }
        a:hover.blade-link {
            background-color: black;
            color: white;
            font-size: 15px;
            font-weight: bold;
            border:solid 1px; 
            border-radius:5px; 
        }
        a.blade-link {
        background-color: white;
            color: black;
            font-size: 15px;
            font-weight: bold;
            border:solid 1px; 
            border-radius:5px; 
            padding:10px;
            min-height:15px; 
            min-width: 80px;
            text-decoration: none;
        }
        table {
            font-family: sans-serif;
            width: 25%;
        }
        td {
            font-weight: bold;
        }
        form {
            margin-top: 50px;
            margin-left: 50px;
            font-family: Google Sans,Roboto,Arial,sans-serif;
            font-size: 15px;
            font-weight: bold
        }

        form div {
            margin-bottom: 20px;
        }

        .navbar {
            position: fixed;
            width: 100%;
        } */
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                @if (Auth::check())
                    <a href="{{ route('home') }}"  class="nav-link">Home</a>
                    <a href="{{ route('syncCalendar') }}"  class="nav-link">Calendars</a>
                    <a href="{{ route('syncEvent') }}"  class="nav-link">Events</a>    
                    {{-- <a href="auth/redirect/calendar"><img src="https://logos-world.net/wp-content/uploads/2021/03/Google-Calendar-Logo-700x394.png" style="width:40px;height:20px" alt="Sync Google Calendar" title="Sync Google Calendar"></a> --}}
                @else
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Calendar') }}
                    </a>     
                @endif
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
