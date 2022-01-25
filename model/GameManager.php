<?php

require_once('../model/ConnectModel.php');

class GameManager {

    private $pdo;

    /**
    * Manage the connexion to the database and store the best scores in session
    */
    public function __construct()
    {
        // Create a new model instance for connect to the database
        $connect = new ConnectModel();
        $this->pdo = $connect->bdConnect();
    }

    /**
     * Retrieve the time of the game and save it in the database
     */
    public function saveGame()
    {
        // We persist the data (temps) in database
        // We retrieve the time from the cookie
        $_SESSION['temps'] = $_COOKIE['temps'];

        // We prepare the query for insert a new row in the table game
        $query = $this->pdo->prepare('INSERT INTO game (name, temps) VALUES (:name, :temps)');
        // Execute the query replacing the marker temps by its value
        $query->execute([
            'temps' => $_SESSION['temps'],
            'name'  => $_SESSION['name']
        ]);
    
        $_SESSION['lastGameId'] = $this->pdo->lastInsertId();
    }
}
