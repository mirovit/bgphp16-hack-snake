@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Waiting room</div>

                <div class="panel-body">
                    <div class="row" id="players">
                        <div class="col-sm-12">No players available.</div>
                    </div>
                <div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        var waitingChannel = pusher.subscribe('presence-waiting-room');

       var drawPlayer = function(member) {
            var player = document.querySelector('#player').innerHTML;

            player = player.replace('href=""', 'href="{{ url('challenge') }}/' + member.id + '"');
            player = player.replace('name">', 'name">' + member.info.name);
            player = player.replace('alt=""', 'alt="' + member.info.name + '"');
            player = player.replace('img src=""', 'img src="' + member.info.avatar + '"');

            document.querySelector('#players').innerHTML += player;
        };

        waitingChannel.bind('pusher:subscription_succeeded', function(members) {
            if(members.count === 1) {
                document.querySelector('#players').innerHTML = '<div class="col-sm-12">No players available.</div>';
            } else {
                members.each(function (member) {
                    if (member.id != members.me.id) {
                        drawPlayer(member);
                    }
                });
            }
        });
    </script>

    <script>
        var challengeChannel = pusher.subscribe('challenge-{{ auth()->user()->id }}');

        challengeChannel.bind('challanged-by', function(data) {
            console.log(data);
        });
    </script>

    <script id="player" type="template/text">
        <div class="col-sm-2">
            <div class="thumbnail">
                <img src="" alt="">
                <div class="caption">
                    <h3 class="user-name"></h3>
                    <p>
                        <a href="" class="btn btn-primary user-challange" role="button">Challange</a>
                    </p>
                </div>
            </div>
        </div>
    </script>
@stop