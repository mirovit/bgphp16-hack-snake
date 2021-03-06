<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Snakes App</title>

    <!-- Styles -->
    <link href="{{ url('/css/app.css') }}" rel="stylesheet">
    <link href="{{ url('/vendor/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ route('app.waiting-room')  }}">
                        Snakes App
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
        <footer class="footer">
            <div class="container">
                <div class="col-sm-4">
                    Created by: <a href="https://twitter.com/albertvision_bg" target="_blank">@albertvision_bg</a>, <a href="https://twitter.com/mvvitanov" target="_blank">@mvvitanov</a>, <a href="https://twitter.com/tkulev" target="_blank">@tkulev</a>
                </div>
                <div class="col-sm-4 text-center">
                    Created in 2016 : )
                </div>
                <div class="col-sm-4">
                    <a href="https://pusher.com" target="_blank" class="pull-right">
                        Powered by <img src="{{ url('images/pusher_logo_dark.png') }}" alt="Pusher" style="height: 30px;" />
                    </a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ url('/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ url('/js/app.js') }}"></script>
    <script src="{{ url('/vendor/sweetalert/sweetalert.min.js') }}"></script>
    <script src="//js.pusher.com/3.2/pusher.min.js"></script>
    <script>
        var pusher = new Pusher('{{ env('PUSHER_KEY') }}', {
            cluster: 'eu',
            encrypted: true,
            authEndpoint: '{{ route('app.game.userCheck') }}',
            auth: {
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                }
            }
        });
    </script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-15510132-7', 'auto');
        ga('send', 'pageview');

    </script>

    @yield('scripts')
</body>
</html>
