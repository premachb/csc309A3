
<!DOCTYPE html>

<html>
	<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="<?= base_url() ?>/js/jquery.timers.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/kineticjs/4.6.0/kinetic.min.js"></script>
	<script>
		var otherUser = "<?= $otherUser->login ?>";
		var user = "<?= $user->login ?>";
		var status = "<?= $status ?>";
		var id = "<?= $user->id ?>";
		var oppenent_id = "<?= $otherUser->id ?>";

		$(function(){
			// Check the current state of the board every second
			$('body').everyTime(1000, function(){
				if(status === "waiting") {
					$.getJSON('<?= base_url() ?>arcade/checkInvitation',function(data, text, jqZHR){
							if (data && data.status=='rejected') {
								alert("Sorry, your invitation to play was declined!");
								window.location.href = '<?= base_url() ?>arcade/index';
							}
							if (data && data.status=='accepted') {
								status = 'playing';
								$('#status').html('Playing ' + otherUser);
								window.location.replace('<?= base_url() ?>board/index');
							}
							
					});
				}
			});

			$('#board').everyTime(1000, function(){
				if(status === "playing"){
					$.getJSON('<?= base_url() ?>board/getMatchState/<?php echo ($match->id); ?>', function(data, text){
						// First check the status of the match, if a player has won we should notify and send both players back to the lobby
						// If we get a board state from the DB, we can keep drawing the game.
						if(data && data.turn){
							if(data.turn == id){
								$('#turnIndicator').html('Its your turn!');
							}
							else{
								$('#turnIndicator').html('Its not your turn!');

							}
						}
						if(data && data.board){
							var board = data.board;
							var x = 30;
							var y = 30;

							var stage = new Kinetic.Stage({
						        container: 'board',
						        width: 600,
						        height: 200
						    });

						    var layer = new Kinetic.Layer();
							for(var i = 0; i < 7; i++){
								for(var j = 0; j < board[i].length; j++){
									if(board[i][j] == 0){
										var color = 'white';
									}

									else if(data.board[i][j] == <?php echo $match->user1_id?>){
										var color = 'red';
									}

									else if(data.board[i][j] == <?php echo $match->user2_id?>){
										var color = 'yellow';
									}

										var circle = new Kinetic.Circle({
									        x: x,
									        y: y,
									        radius: 20,
									        fill: color,
									        stroke: 'black',
									        strokeWidth: 4,
									        column: i
								      	});

								      	
										// Attach click event to each circle seperately 
								      	circle.on('click', function(){
								      		var mouseXY = stage.getMousePosition();
								      		column_clicked = Math.ceil(mouseXY.x / 30);
								   
								      		if(column_clicked - 1 < 0){
								      			column_clicked = 0;
								      		}
								      		else{
								      			column_clicked = column_clicked - 1;
								      		}

								      		console.log(column_clicked);

								      		// It's the users turn and they can actually make a move
								      		if(data.turn && data.turn == id){
								      			// PLACE THE CHIP
								      			var row_iterator = 1;
								      		
								      			if(board[column_clicked][row_iterator - 1] != 0){
								      				console.log('column is full')
								      			}

								      			else{
								      				// Iterate down the column until spot is not empty or we reached the bottom
									      			while(board[column_clicked][row_iterator] == 0 && row_iterator < 6){
									      				row_iterator++;
									      			}

									      			board[column_clicked][row_iterator - 1] = id;
									      			var lastChip = [column_clicked, row_iterator - 1];
					      							
					      							// Send new board state to the DB and change turns 
									      			$.ajax({
														type: "POST",
														url: "<?= base_url() ?>board/updateMatchState/<?php echo ($match->id); ?>",
														data: {board: board, turn: oppenent_id, lastChip: lastChip, lastUser: id},
													});

													$

								      			}
								      		}
								      	});
										layer.add(circle);
										y+= 30;
								}

								y = 30;
								x += 30;
						}
						stage.add(layer);
					}

					// Let the turn finish and check for a winner, if there is one report it and send both users back to the arcade page.
					if(data.match_status != 1){
							if(data.match_status == 2 && (<?php echo $match->user1_id?> == id)){
								alert("You won!");
								window.location.replace('<?= base_url() ?>arcade');
							}
							else if(data.match_status == 3 && (<?php echo $match->user2_id?> == id)){
								alert("You won!");
								window.location.replace('<?= base_url() ?>arcade');
							}
							else{
								alert("You lost!");
								window.location.replace('<?= base_url() ?>arcade');
							}
					}
					});
				}
			});
		});
	</script>
	</head> 
<body>  
	<h1>Game Area</h1>

	<div>
	Hello <?= $user->fullName() ?>  <?= anchor('account/logout','(Logout)') ?>  
	</div>
	
	<div id='status'> 
	<?php 
		if ($status == "playing")
			echo "Playing " . $otherUser->login;
		else
			echo "Waiting on " . $otherUser->login;
	?>
	</div>

	<div id='turnIndicator'></div>
	<div id='board'></div>
</body>

</html>

