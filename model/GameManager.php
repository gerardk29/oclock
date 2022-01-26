<?php
// We include this class to call usefull methods we will find below
require_once('../model/ConnectModel.php');

class GameManager {

    /**
     * Attribute to store the connexion of the database
     */
    private $pdo;

    /**
    * Manage the connexion to the database
    */
    public function __construct()
    {
        // Create a new model instance to connect to the database
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

        // We will use a prepared query to separate the query and the data.
        // Benefits of prepared queries are : efficiency (because they can be used repeatedly without re-compiling) and security by reducing or eliminating SQL injection attacks.
        // We prepare the query to insert a new row in the table game
        $query = $this->pdo->prepare('INSERT INTO game (name, temps) VALUES (:name, :temps)');
        // Execute the query replacing the markers temps and name by their value
        $query->execute([
            'temps' => $_SESSION['temps'],
            'name'  => $_SESSION['name']
        ]);
        // We store the last game id inserted in the SESSION to use it after in the page game.php
        // (the success message will display if the session contains values for the keys name and lastGameId)
        $_SESSION['lastGameId'] = $this->pdo->lastInsertId();
    }
}
