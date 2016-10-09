@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Waiting room</div>

                <div class="panel-body">
                    <div class="row" id="players">
                        <div class="col-sm-12" id="noAvailablePlayers">No players available.</div>
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

        var players = $('#players');
        var currentPlayersCount = 0;

       var drawPlayer = function(member) {
           hideNoPlayers();
           currentPlayersCount += 1;
            var player = $($('#player').html());

           player.attr('id', 'user-' + member.id);
           player.find('.user-challange').attr('href', '{{ url('challenge') }}/' + member.id);
           player.find('.user-name').html(member.info.name);
           player.find('.user-avatar').attr('alt', member.info.name);
           player.find('.user-avatar').attr('src', member.info.avatar);

           players.append(player);
        };

        var removePlayer = function(member) {
            currentPlayersCount -= 1;

            $('#user-' + member.id).remove();

            if(currentPlayersCount <= 0) {
                showNoPlayers();
            }
        };

        var showNoPlayers = function() {
            $('#noAvailablePlayers').show();
        };

        var hideNoPlayers = function() {
            $('#noAvailablePlayers').hide();
        };

        waitingChannel.bind('pusher:subscription_succeeded', function(members) {
            if(members.count === 1) {
                showNoPlayers();
            } else {
                hideNoPlayers();
                members.each(function (member) {
                    if (member.id != members.me.id) {
                        drawPlayer(member);
                    }
                });
            }
        });

        waitingChannel.bind('pusher:member_added', function(member) {
            drawPlayer(member);
        });

        waitingChannel.bind('pusher:member_removed', function(member) {
            removePlayer(member);
        });
    </script>

    <script>
        var challengeChannel = pusher.subscribe('private-challenge-{{ auth()->user()->id }}');

        challengeChannel.bind('challanged-by', function(data) {
            var waitingChannel = pusher.subscribe('private-waiting-' + data.game_uuid);
            swal({
                title: 'Challenge Request',
                text: 'You\'ve been challanged to play a game by ' + data.user.name,
                showCancelButton: true,
                confirmButtonText: 'Accept',
                cancelButtonText: 'Decline',
            }, function(isConfirm){
                if( isConfirm ) {
                    waitingChannel.trigger('client-accepted', {challenged: JSON.parse('{!! auth()->user()->toJson() !!}'), challenger: data.user});
                    setTimeout(function() {
                        window.location = '{{ url('game') }}/' + data.game_uuid;
                    }, 200);
                } else {
                    waitingChannel.trigger('client-declined', {user: JSON.parse('{!! auth()->user()->toJson() !!}')});
                }
            });
        });
    </script>

    <script id="player" type="template/text">
        <div class="col-sm-3" id="user-">
            <div class="thumbnail">
                <img src="" alt="" class="user-avatar">
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