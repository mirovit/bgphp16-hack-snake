<html>
<head>
    <title>Snake Game</title>
    <style>
        body, html {
            margin: 0;padding:0;
        }
    </style>
</head>
<body onload="checkCanvasIsSupported();" style="background: #000">

<script src="/js/app.js"></script>
<script src="//js.pusher.com/3.2/pusher.min.js"></script>
<script>
    //Pusher.logToConsole = true;
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

<script type="text/javascript" src="{{ url('js/init.js') }}"></script>
<script type="text/javascript" src="{{ url('js/snake.js?v=1') }}"></script>
<script type="text/javascript" src="{{ url('js/game.js') }}"></script>
<canvas id="game_canvas"></canvas><br/>

<script type="text/javascript">
    var challenger = {
        id: {{ $challenger->id }},
        name: "{{ $challenger->name }}"
    };
    var challenged = {
        id: {{ $challenged->id }},
        name: "{{ $challenged->name }}"
    };
    var my = {
        id: {{ auth()->user()->id }},
        name: "{{ auth()->user()->name }}"
    };
    var interval;

    var gameChannel = pusher.subscribe('private-room-' + challenger.id + '-' + challenged.id);
    var waitingChannel = pusher.subscribe('presence-room-' + challenger.id + '-' + challenged.id);

    function handleMembers(){
        var members = waitingChannel.members;
        //console.log("HANDLE MEMBERS:", members)
        if (members.count == 2) {

            var dataPlayer = {
                id: my.id,
                name: my.name,
                position: snake.body[0]
            };

            setTimeout(function() {
                gameChannel.trigger('client-addPlayer', dataPlayer);
            }, 2000);

        }

    }

    waitingChannel.bind('pusher:subscription_succeeded', handleMembers)
    waitingChannel.bind('pusher:member_added', handleMembers)

    gameChannel.bind('client-addPlayer', function(data) {
        document.dispatchEvent(new CustomEvent('addPlayer', {
            detail: data
        }));
    });


    gameChannel.bind('client-startGame', function(data) {
        document.dispatchEvent(new CustomEvent('startGame', {
            detail: data
        }));
    });

    gameChannel.bind('client-remoteMove', function(data) {
        remoteSnake.score = data.points;
        remoteSnake.update_direction(data.direction);
        remoteSnake.body = data.body;
    });

    gameChannel.bind('client-newFood', function(data) {
        game.food = data.foodPosition;
    });

    document.addEventListener('addPlayer', function(e) {
        data = e.detail;

        remoteSnake = new Snake(canvas, context, 10, game, true);
        remoteSnake.snakeId = data.id;
        remoteSnake.snakeName = data.name;
        remoteSnake.init();
        remoteSnake.addAtPosition(data.position);
        //console.log('Iam ', my.name);
        //console.log(data.position, ' for ', data.name);

        gameChannel.trigger('client-startGame', {
            'foodPosition': {x: 100, y: 500},
            'board': {width: 100, height: 100}
        });
    });

    document.addEventListener('removePlayer', function(e) {
        //dispatch win local user
    });

    document.addEventListener('newFood', function(e) {
        data = e.detail;

        game.food = data.foodPosition;
        gameChannel.trigger('client-newFood', data);
    });

    document.addEventListener('startGame', function(e) {
        data = e.detail;

        console.log('starttttt');
        //if (game.gam)
        game.newFood(data.foodPosition);
        render();
        interval = setInterval(render, 100);
    });

    document.addEventListener('endGame', function(e) {
        game.game_over = true;
    });

    document.addEventListener('win', function(e) {
        game.game_win = true;
    });

    function sendEvent(name, data) {
        gameChannel.trigger('client-' + name, data);
    }

</script>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-15510132-7', 'auto');
    ga('send', 'pageview');

</script>

</body>
</html>