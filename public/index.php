<?php
// We include this class for call some usefull methods
require_once('../controller/GameController.php');

$game = new GameController();

// We include the similar lines of pages in header.html
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
		// We test if we are in success mode (i.e. the gamer win)
		// If yes, the function will save the game in database
		$game->isSuccessMode();

		// We store the name in the session
        if (isset($_POST['name'])) {
            $_SESSION['name'] = $_POST['name'];
        }
		// Display the 5 best scores from GameRepository
        if (isset($_SESSION['data'])) {
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
		<form action="game.php" method="POST" class="form_name">
			<input type="text" name="name" id="name" placeholder="Renseignez votre nom" required/>
			<button type="submit">JOUER !</button>
		</form>
	</div>
<?php include_once('footer.html'); ?>
