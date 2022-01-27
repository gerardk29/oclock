<?php
// We include this class to call usefull methods we will find below
require_once('../controller/GameController.php');

$game = new GameController();

// We factorize in header.html the similar lines of index.php and game.php 
// and retrieve the content by this function
include_once('header.html'); ?>

	<div class="parent">
		<table>
			<thead>
				<tr>
					<th>Meilleurs temps</th>
				</tr>
			</thead>
			<tbody>
		<?php 
		// We test if we are in the "success mode" (i.e. the player wins)
		// If yes, the function will save the game in database
		$game->isSuccessMode();

		// We store the name in the session
        if (isset($_POST['name'])) {
            $_SESSION['name'] = $_POST['name'];
        }
		// We display the top 5 scores from session (GameRepository did the job)
        if (isset($_SESSION['data'])) {
			// We loop on the session array to display the name and time of the top 5 scores
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
		<!-- We add a form allowing to fill the player name -->
		<form action="game.php" method="POST" class="form_name">
			<input type="text" name="name" id="name" placeholder="Renseignez votre nom" required/>
			<button type="submit">JOUER !</button>
		</form>
	</div>

<?php // We factorize in footer.html the similar lines of index.php and game.php 
// and retrieve the content by this function	
include_once('footer.html'); ?>
