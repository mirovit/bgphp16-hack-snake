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
    var pusher = new Pusher('{{ env('PUSHER_KEY') }}', {
        cluster: 'eu',
        encrypted: true,
        authEndpoint: '{{ url('user/check') }}',
        auth: {
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            }
        }
    });
</script>

<script type="text/javascript" src="{{ url('js/init.js') }}"></script>
<script type="text/javascript" src="{{ url('js/snake.js') }}"></script>
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

    waitingChannel.bind('pusher:member_added', function(member) {
        console.log('member added');
        if (member.id == my.id) {
            //return;
        }
        var dataPlayer = {
            id: member.id,
            name: member.info.name,
            position: snake.getRandomPosition()
        };

        gameChannel.trigger('client-addPlayer', dataPlayer);

        setTimeout(function () {
            gameChannel.trigger('client-startGame', {
                'foodPosition': {x: 100, y: 500},
                'board': {width: 100, height: 100}
            });
        }, 1000);

    });

    waitingChannel.bind('pusher:subscription_succeeded', function(members) {
        if (members.count!=2) return;

        members.each(function (member) {
            if (member.id != members.me.id) {
 //              return;
            }

            var dataPlayer = {
                id: my.id,
                name: my.name,
                position: snake.getRandomPosition()
            };


            gameChannel.trigger('client-addPlayer', dataPlayer);

        });


    });







    gameChannel.bind('client-addPlayer', function(data) {
        console.log('add player called');
        console.log(data);
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
    });

    document.addEventListener('addPlayer', function(e) {
        data = e.detail;

        remoteSnake = new Snake(canvas, context, 10, game, true);
        remoteSnake.snakeId = data.id;
        remoteSnake.snakeName = data.name;
        remoteSnake.init();
        remoteSnake.addAtPosition(data.position);
    });

    document.addEventListener('removePlayer', function(e) {
        //dispatch win local user
    });

    document.addEventListener('startGame', function(e) {
        data = e.detail;

        console.log('starttttt');

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

    function test()
    {
        var dataPlayer = {
            detail: {
                position: {x: 0, y: 0},
                id: 5,
                name: 'Miro'
            }
        };

        var dataGame = {
            detail: {
                'foodPosition': {x: 100, y: 500},
                'board': {width: 100, height: 100}
            }
        };
        document.dispatchEvent(new CustomEvent('addPlayer', dataPlayer));
        document.dispatchEvent(new CustomEvent('startGame', dataGame));

        setInterval(randomPosition, 500);
    }

    function randomPosition()
    {
        var data = {
            detail: {
                'direction': Math.floor(getRandomRange(0,4)),
                'id' : 5,
                'points': 100
            }
        };

        document.dispatchEvent(new CustomEvent('remoteMove', data));
    }

</script>

</body>
</html>
