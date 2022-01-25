<?php

class ConnectModel
{
    /**
     * Connexion to the database
     * @return PDO object
     */
    function bdConnect()
    {
        try
        {
            return new PDO('mysql:host=localhost;dbname=memory;charset=utf8', 'root', '');
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }
    }
}
