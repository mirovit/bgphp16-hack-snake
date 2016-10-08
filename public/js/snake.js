//-----------------------------------------------------------------------
// Snake game
//
// Author: delimitry
//-----------------------------------------------------------------------

function Point(x, y) {
	this.x = x;
	this.y = y;

	this.collideWith = function(x, y) {
		return this.x == x && this.y == y;
	}
}

var SnakeDirections = {
	UP : 0,
	DOWN : 1,
	LEFT : 2,
	RIGHT : 3
}

function getRandomRange(min, max) {
	return Math.random() * (max - min + 1) + min;
}

function getRandomColor(){
    var r = Math.floor(getRandomRange(70, 255));
    var g = Math.floor(getRandomRange(70, 255));
    var b = Math.floor(getRandomRange(70, 255));

    return 'rgb(' + r + ',' + g + ',' + b + ')';
}



function Snake(canvas, context, point_size, game, isRemote) {

	this.score = 0;
	this.game_paused = false;
	this.direction = SnakeDirections.RIGHT;
	this.point_size = point_size;
	this.body = new Array();
    this.isRemote = isRemote;
    this.game = game;
    this.headColor;
    this.tailColor;
    this.snakeName;
    this.snakeId;

	this.init = function() {
		this.score = 0;

		this.direction = SnakeDirections.RIGHT;
		this.body = new Array();

        this.headColor = 'rgb(255, 255, 255)';
        this.tailColor = getRandomColor();

	}
    this.getRandomPosition = function() {
            pos_x = Math.floor(getRandomRange(0, canvas.width / 2) / this.point_size) * this.point_size;
            pos_y = Math.floor(getRandomRange(0, canvas.height) / this.point_size) * this.point_size;

            return new Point(pos_x, pos_y);
    }
    this.addAtPosition = function(position) {
        // init snake body
        for (var i = 0; i < 3; i++) {
            this.body.push(new Point(position.x, position.y));
        };
    }

	this.draw = function() {
		for (var i = this.body.length-1; i >= 0; i--) {
			if (i == 0) {
                context.fillStyle = this.headColor;
            } else {
                context.fillStyle = this.tailColor;
            }
			context.fillRect(this.body[i].x, this.body[i].y, this.point_size, this.point_size);
		};

        this.game.drawText();

		context.fillStyle = 'rgb(255,255,0)';
		context.font = 'bold 15px Arial';
        var scorePosition = new Point(5, 15);

        if (this.isRemote) {
            scorePosition.y += 20;
        }
		context.fillText(this.snakeName + '\'s score: '+ this.score, scorePosition.x, scorePosition.y);
	}

	this.toggle_pause = function() {
		this.game_paused = !this.game_paused;
	}

	this.update_direction = function(direction) {
		if (this.direction == SnakeDirections.LEFT && direction == SnakeDirections.RIGHT) return;
		if (this.direction == SnakeDirections.RIGHT && direction == SnakeDirections.LEFT) return;
		if (this.direction == SnakeDirections.UP && direction == SnakeDirections.DOWN) return;
		if (this.direction == SnakeDirections.DOWN && direction == SnakeDirections.UP) return;
		this.direction = direction;
	}


	this.update = function() {
		if (this.game.game_over || this.game_win || this.game_paused) return;
		step = 10;
		switch (this.direction) {
			case SnakeDirections.LEFT:
				if (this.body[0].x > 0) {
					this.body.unshift(new Point(this.body[0].x - step, this.body[0].y));
					this.body.pop();
				} else {
					this.body[0].x = 0;
					this.game.game_over = true;
				}
				break;

			case SnakeDirections.UP:
				if (this.body[0].y > 0) {
					this.body.unshift(new Point(this.body[0].x, this.body[0].y - step));
					this.body.pop();
				} else {
					this.body[0].y = 0;
					this.game.game_over = true;
				}
				break;

			case SnakeDirections.RIGHT:
				if (this.body[0].x < canvas.width - this.point_size) {
					this.body.unshift(new Point(this.body[0].x + step, this.body[0].y));
					this.body.pop();
				} else {
					this.body[0].x = canvas.width - this.point_size;
					this.game.game_over = true;
				}
				break;

			case SnakeDirections.DOWN:
				if (this.body[0].y < canvas.height - this.point_size) {
					this.body.unshift(new Point(this.body[0].x, this.body[0].y + step));
					this.body.pop();
				} else {
					this.body[0].y = canvas.height - this.point_size;
					this.game.game_over = true;
				}
				break;
		}

		// check for self collision
		if (this.body.length > 1) {
			for (var i = 1; i < this.body.length; i++) {
				if (this.body[0].collideWith(this.body[i].x, this.body[i].y)) {
					this.game.game_over = true;
				}
			}
		}

		if (this.body[0].collideWith(this.game.food.x, this.game.food.y)) {
            this.game.newFood();

			this.body.push(new Point(this.game.food.x, this.game.food.y));
			this.score += 10;
			if (this.score > 250) {
				this.game_win = true;
			}
		}
	}

}
