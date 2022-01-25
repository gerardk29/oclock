<?php
require_once('../controller/GameController.php');

$game = new GameController();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Memory Game</title>
	<link href="css/style.css" rel="stylesheet">
</head>
<body>
    <h1>Jeu de mémoire</h1>
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
</body>
</html>