<?php
require_once('../controller/GameController.php');
require_once('../model/ConnectModel.php');
require_once('../model/GameRepository.php');

$game = new GameController();
$connect = new ConnectModel();
$repository = new GameRepository();
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
		<div class="table">
			<?php
			// We retrieve the game parameters (like the number of rows and columns)
				$board = $game->getBoard();
				$rows = $game->getNumberOfRows();
				$columns = $game->getNumberOfColumns();

				$k = 0;
				// Build the game matrix (dependent of the number of rows and columns)
				for ($i = 0; $i < $rows; $i++) {
					echo '<div class="row">';
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
		<form action="" method="POST">
			<input type="text" name="chrono" id="chrono" value="00:00" size="8"/>
		</form>
	</div>
	<div id='progressbar'></div>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="assets/js/script.js"></script>
</body>
</html>