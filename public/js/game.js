function Game(canvas, context, point_size) {

	this.game_over = false;
	this.game_win = false;
	this.game_paused = false;
	this.point_size = point_size;
	this.food = new Point();
	this.game_started = false;

	this.getNewFoodPosition = function() {
		food_x = Math.floor(getRandomRange(0, canvas.width - this.point_size) / this.point_size) * this.point_size;
		food_y = Math.floor(getRandomRange(0, canvas.height - this.point_size) / this.point_size) * this.point_size;

		return new Point(food_x, food_y);
	}

	this.newFood = function(position) {
		this.food = new Point(position.x, position.y);
	}

	this.draw = function() {
		context.fillStyle = 'rgb(255,0,255)';
		context.fillRect(this.food.x, this.food.y, this.point_size, this.point_size);
	}

	this.drawText = function() {
		if (this.game_over) {
			context.fillStyle = 'rgb(255,255,0)';
			context.font = 'bold 20px Arial';
			context.fillText('Game Over', canvas.width / 2 - 50, canvas.height / 2);
		}

		if (this.game_win) {
			context.fillStyle = 'rgb(255,255,0)';
			context.font = 'bold 20px Arial';
			context.fillText('You Win', canvas.width / 2 - 30, canvas.height / 2);
		}
	}
}