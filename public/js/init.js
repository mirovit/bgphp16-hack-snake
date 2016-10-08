var LEFT = 37;
var UP = 38;
var RIGHT = 39;
var DOWN = 40;
var SPACE = 32;

document.onkeydown = function(event) {
	var keyCode;
	if (event == null) {
		keyCode = window.event.keyCode;
	} else {
		keyCode = event.keyCode;
	}

    if ([LEFT, UP, RIGHT, DOWN].indexOf(keyCode) == -1) {
        return;
    }

	switch (keyCode) {
		case LEFT:
			snake.update_direction(SnakeDirections.LEFT);
			break;
		case UP:
			snake.update_direction(SnakeDirections.UP);
			break;
		case RIGHT:
			snake.update_direction(SnakeDirections.RIGHT);
			break;
		case DOWN:
			snake.update_direction(SnakeDirections.DOWN);
			break;
		case SPACE:
            //render();
            //setInterval(render, 100);
			break;
		default:
			break;
	}
    //!!!!
    sendEvent('remoteMove', {'direction': snake.direction});
}

var canvas;
var context;
var snake;
var remoteSnake;

function checkCanvasIsSupported() {
    var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
    var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0)

	canvas = document.getElementById("game_canvas");
	canvas.width = w;
	canvas.height = h;
	if (canvas.getContext) {
		context = canvas.getContext('2d');
        game = new Game(canvas, context, 10);

		snake = new Snake(canvas, context, 10, game, false);
        snake.snakeId = my.id;
        snake.snakeName = my.name;
        snake.init();

        var position = snake.getRandomPosition();
        snake.addAtPosition(position);





	} else {
		alert("Sorry, but your browser doesn't support a canvas.");
	}
}

function render() {
	context.clearRect(0, 0, canvas.width , canvas.height);
	snake.update();
    snake.draw();
	remoteSnake.update();
	remoteSnake.draw();
    game.draw();
}
