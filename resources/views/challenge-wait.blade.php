@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Waiting response</div>

                <div class="panel-body">
                    Waiting for a response ...
                <div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        var challengeChannel = pusher.subscribe('private-waiting-{{ $challenged->id }}-{{ $me->id }}');

        challengeChannel.bind('client-accepted', function(data) {
            window.location = '{{ url("game/{$challenged->id}/{$me->id}") }}';
        });

        challengeChannel.bind('client-declined', function(data) {
            alert('Your request has been declined.');

            setTimeout(function() {
                window.location = '{{ url("/") }}';
            }, 1000);
        });
    </script>
@stop