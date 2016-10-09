function getRandomRange(min, max) {
	return Math.random() * (max - min + 1) + min;
}

function Game(canvas, context, point_size) {
	this.game_over = false;
	this.game_win = false;
	this.game_paused = false;
	this.point_size = point_size;
	this.food = new Point();
	this.canvas = canvas;

	this.snake;
	this.remoteSnake;
	
	this.getNewFoodPosition = function() {
		food_x = Math.floor(getRandomRange(0, this.canvas.width - this.point_size) / this.point_size) * this.point_size;
		food_y = Math.floor(getRandomRange(0, this.canvas.height - this.point_size) / this.point_size) * this.point_size;

		return new Point(food_x, food_y);
	};

	this.start = function(foodPosition, snake, remoteSnake) {
		this.game_over = false;
		this.game_win = false;

		this.snake = snake;
		this.remoteSnake = remoteSnake;

		if(typeof foodPosition == 'undefined') {
			foodPosition = this.getNewFoodPosition();
		}

		this.newFood(foodPosition);
		setInterval(this.render.bind(this), 100);
	};

	this.draw = function() {
		context.fillStyle = 'rgb(255,0,255)';
		context.fillRect(this.food.x, this.food.y, this.point_size, this.point_size);
	};

	this.render = function() {
		context.clearRect(0, 0, canvas.width , canvas.height);

		this.snake.update();
		this.snake.draw();
		this.remoteSnake.update();
		this.remoteSnake.draw();

		this.draw();
	};

	this.newFood = function(position) {
		this.food = new Point(position.x, position.y);
	};

	this.drawText = function() {
		if (this.game_over) {
			context.fillStyle = 'rgb(255,255,0)';
			context.font = 'bold 20px Arial';
			context.fillText('Game Over', this.canvas.width / 2 - 50, this.canvas.height / 2);
		}

		if (this.game_win) {
			context.fillStyle = 'rgb(255,255,0)';
			context.font = 'bold 20px Arial';
			context.fillText('You Win', this.canvas.width / 2 - 30, this.canvas.height / 2);
		}
	}
	this.getRandomPosition = function() {
		var pos_x = Math.floor(getRandomRange(0, this.canvas.width / 2) / this.point_size) * this.point_size;
		var pos_y = Math.floor(getRandomRange(0, this.canvas.height) / this.point_size) * this.point_size;

		return new Point(pos_x, pos_y);
	};
}
