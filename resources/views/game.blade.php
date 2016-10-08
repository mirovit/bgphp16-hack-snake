@extends('layouts.app')

@section('content')
    <div class="container">
        <div id="parent"></div>
        <div id="receiver"></div>
    </div>
@stop

@section('scripts')
    <script src="/js/snake.js"></script>
    <script type="text/javascript">

        // If you are using jQuery, use < $(document).ready(function(){ ... }) > instead
        document.addEventListener("DOMContentLoaded", function(){

            // The DOM-element which will hold the playfield
            // If you are using jQuery, you can use < var element = $("#parent"); > instead
            var parentElement = document.getElementById("parent");
            var receiverElement = document.getElementById("receiver");
            // User defined settings overrides default settings.
            // See snake-js.js for all available options.
            var settings = {
                frameInterval : 120,
                backgroundColor : "#f3e698",
                receiver: false
            };

            var settingsReceiver = {
                frameInterval : 120,
                backgroundColor : "#f3e698",
                receiver: true
            };

            var game = new SnakeJS(parentElement, settings);
            var receiber = new SnakeJS(receiverElement, settingsReceiver);
        }, true);

    </script>
@stop