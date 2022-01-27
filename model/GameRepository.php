<?php
// We include this class to call usefull methods we will find below
require_once('../model/ConnectModel.php');

/**
 * Request the database to display the best scores and store them in session
 */
class GameRepository {

    /**
    * Request the top 5 results and store them in session
    */
    public function __construct()
    {
        // Create a new model instance to connect to the database
        $connect = new ConnectModel();
        $pdo = $connect->bdConnect();
        // Retrieve the top 5 scores from the table game with a SELECT query, ordered by the time (ascendant) and limited to top 5
        // We store the result (array) in the session
        $_SESSION['data'] = $pdo->query('SELECT * FROM game ORDER BY temps LIMIT 5')->fetchAll();
    }
}
