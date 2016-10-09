<html>
<head>
    <title>Snake Game</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
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
<canvas id="game_canvas"></canvas>
<br/>

<script type="text/javascript">
    function checkCanvasIsSupported() {
        var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

        canvas = document.getElementById("game_canvas");
        canvas.width = w;
        canvas.height = h;

        if (!canvas.getContext) {
            alert("Sorry, but your browser doesn't support a canvas.");
        }

        context = canvas.getContext('2d');
        game = new Game(canvas, context, 10);

        var challenger = {
            id: {{ $challenger->id }},
            name: "{{ $challenger->name }}"
        };
        var challenged = {
            id: {{ $challenged->id }},
            name: "{{ $challenged->name }}"
        };
        var me = {
            id: {{ auth()->user()->id }},
            name: "{{ auth()->user()->name }}"
        };
        var interval;

        var roomId = 'room-' + challenger.id + '-' + challenged.id;

        var gameChannel = pusher.subscribe('private-' + roomId);
        var waitingChannel = pusher.subscribe('presence-' + roomId);

        var snake;
        var remoteSnake;
        var myStartPos = game.getRandomPosition();

        waitingChannel.bind('pusher:subscription_succeeded', function (members) {
            if (members > 2) {
                alert('Room full');

                return;
            }

//            snake = new Snake(game, members.me.id, members.me.info.name, false);
//            snake.addAtPosition(myStartPos);

            gameChannel.trigger('client-addSnake', {
                id: members.me.id,
                name: members.me.info.name,
                position: {
                    x: myStartPos.x,
                    y: myStartPos.y
                }
            });

//            if (members.count == 1) {
//                waitingChannel.bind('pusher:member_added', function (member) {
//
//                    if (member.id == members.me.id) {
//                        return;
//                    }
//
//                    buildRemoteSnake(member);
////                    gameChannel.trigger('client-addSnake', {
////                        id: snake.snakeId,
////                        name: snake.snakeName,
////                        position: {
////                            x: myStartPos.x,
////                            y: myStartPos.y
////                        }
////                    });
//
//                });
//            } else {
//                members.each(function (member) {
//                    if (member.id == members.me.id) {
//                        return;
//                    }
//
//                    buildRemoteSnake(member);
//                });
//            }
        });

        function buildRemoteSnake(member) {
            if (typeof member.position == 'undefined') {
                member.position = game.getRandomPosition();
            }

            remoteSnake = new Snake(game, member.id, member.info.name, true);
            remoteSnake.addAtPosition(game.getRandomPosition());

            console.log(snake, remoteSnake);
        }

        function triggerAll(channel, event, attr) {
            channel.trigger(event, attr);

//        if(typeof channel.callbacks._callbacks['_'+event][0].fn == 'function') {
//            channel.callbacks._callbacks['_'+event][0].fn.apply(null, attr);
//        }

        }

        gameChannel.bind('addSnake', function (member) {
            console.log(member);
//            if (member.id == snake.snakeId) {
//                return;
//            }
//            console.log('new snake');
//
//            buildRemoteSnake(member);
        });

        gameChannel.bind('client-startGame', function (data) {
            document.dispatchEvent(new CustomEvent('startGame', {
                detail: data
            }));
        });

        gameChannel.bind('client-remoteMove', function (data) {
            remoteSnake.score = data.points;
            remoteSnake.update_direction(data.direction);
        });

        document.addEventListener('removePlayer', function (e) {
            //dispatch win local user
        });

        document.addEventListener('startGame', function (e) {
            var data = e.detail;

            console.log(snake, remoteSnake);

//            game.start(data.foodPosition, snake, remoteSnake);
        });

        document.addEventListener('endGame', function (e) {
            game.game_over = true;
        });

        document.addEventListener('win', function (e) {
            game.game_win = true;
        });
    }


    //
    //    function sendEvent(name, data) {
    //        console.log('send triggered');
    //        gameChannel.trigger('client-' + name, data);
    //    }
    //
    //    function randomPosition()
    //    {
    //        var data = {
    //            detail: {
    //                'direction': Math.floor(getRandomRange(0,4)),
    //                'id' : 5,
    //                'points': 100
    //            }
    //        };
    //
    //        document.dispatchEvent(new CustomEvent('remoteMove', data));
    //    }

</script>

</body>
</html>
