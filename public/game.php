<?php
// We include these classes to call usefull methods we will find below
require_once('../controller/GameController.php');
require_once('../model/ConnectModel.php');
require_once('../model/GameRepository.php');

$game = new GameController();
$connect = new ConnectModel();
$repository = new GameRepository();

// We factorize in header.html the similar lines of index.php and game.php 
// and retrieve the content by this function
include_once('header.html'); ?>
    
	<h2>Vous avez 2 min !...</h2>
	<div class="parent">
		<table>
			<thead>
				<tr>
					<th>Meilleurs temps</th>
				</tr>
			</thead>
			<tbody>
		<?php 
		// We test if we are in success mode (i.e. the player wins)
		// If yes, the function will save the game in database
		$game->isSuccessMode();	

		// We store the name in the session
        if (isset($_POST['name'])) {
            $_SESSION['name'] = $_POST['name'];
        }

		// Display the top 5 scores from session (GameRepository did the job)
        if (isset($_SESSION['data'])) {
			// We loop on the session array to display the name and time of top 5 scores
            foreach ($_SESSION['data'] as $row) {
                ?>
                <tr>
                    <td><?php echo $row['name'];?></td>
                    <td><?php echo $row['temps'];?></td>
                </tr>
                <?php
            }
        }
	?>			
			</tbody>
		</table>
		<div class="table">
			<?php
			// We retrieve the game parameters (like the number of rows and columns)
				$board = $game->getBoard();
				$rows = $game->getNumberOfRows();
				$columns = $game->getNumberOfColumns();

				$k = 0;
				// Build the game matrix (dependent on the number of rows and columns)
				// Loop on the row and build a parent div
				for ($i = 0; $i < $rows; $i++) {
					echo '<div class="row">';
					// For each row, build the columns  in several child div
					for ($j = 0; $j < $columns; $j++) {
			?>
					<div class="card" data-index="<?php echo $k ?>"></div>
			<?php
					$k++;
					}
					echo "</div>";
				}
			?>
		</div>
		<!-- Add a chronometer (2 minutes time, set in script.js) -->
		<form action="" method="POST">
			<input type="text" name="chrono" id="chrono" value="00:00" size="8"/>
		</form>
	</div>
	<div id='progressbar'></div>
	<?php

		// If the session contains values for the keys name and lastGameId, it means that the player wins
		// So we can display a success message
		if (isset($_SESSION['name']) && isset($_SESSION['lastGameId'])) { ?>
				<div class="success_message">
					<?php echo 'Bien joué ' . $_SESSION['name'] . ',vous avez réussi le memory en ' . $_SESSION['temps'] . '!!!' .'<br/>';?>
					<?php echo "C'est reparti !";?>
				</div>
				<?php
				// We unset the lastGameId in order to initialize the next game (for the same player)
				unset($_SESSION['lastGameId']);
			}
		?>
	<!-- Include the jQuery library and our JS script -->
	<!-- Better to include these at the end of the file just in case of JS bug -->
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="assets/js/script.js"></script>
<?php // We factorize in footer.html the similar lines of index.php and game.php 
// and retrieve the content by this function
include_once('footer.html'); ?>
