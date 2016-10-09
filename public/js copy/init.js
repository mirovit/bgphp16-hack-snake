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
    // sendEvent('remoteMove', {'direction': snake.direction});
}

var game;
var canvas;
var context;
var snake;
var remoteSnake;


