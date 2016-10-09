@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Waiting response</div>

                <div class="panel-body">
                    Waiting for {{ $challenged->name }} to respond to your request.
                <div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        var challengeChannel = pusher.subscribe('private-waiting-{{ $game->game_uuid }}');

        challengeChannel.bind('client-accepted', function() {
            window.location = '{{ url("game/{$game->game_uuid}") }}';
        });

        challengeChannel.bind('client-declined', function(data) {
            swal({
                title: "Your request has been declined",
                text: "Sorry, but " + data.user.name + " declined your request. You'll be sent to the waiting room now.",
                showCancelButton: false,
            }, function() {
                window.location = '{{ url("/") }}';
            });
        });
    </script>
@stop