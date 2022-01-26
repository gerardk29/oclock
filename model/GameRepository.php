<?php
// We include this class to call usefull methods we will find below
require_once('../model/ConnectModel.php');

class GameRepository {

    /**
    * Manage the connexion to the database and store the best scores in session
    */
    public function __construct()
    {
        // Create a new model instance to connect to the database
        $connect = new ConnectModel();
        $pdo = $connect->bdConnect();
        // Retrieve the top 5 scores from the table game by SELECT, ordered by the time (ascendant) and limited to top 5
        // We store the result (array) in the session
        $_SESSION['data'] = $pdo->query('SELECT * FROM game ORDER BY temps LIMIT 5')->fetchAll();
    }
}
