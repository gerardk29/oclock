<?php

require_once('../model/ConnectModel.php');

class GameRepository {

    /**
    * Manage the connexion to the database and store the best scores in session
    */
    public function __construct()
    {
        // Create a new model instance for connect to the database
        $connect = new ConnectModel();
        $pdo = $connect->bdConnect();
        // Retrieve the 5 best scores from the table game, ordered by the time
        // We store the result (array) in the session
        $_SESSION['data'] = $pdo->query('SELECT * FROM game ORDER BY temps LIMIT 5')->fetchAll();
    }
}
