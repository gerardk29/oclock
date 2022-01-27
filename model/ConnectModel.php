<?php

class ConnectModel
{
    /**
     * Connexion to the database
     * @return PDO object
     */
    function bdConnect()
    {
        // The connexion to the database if sensitive : we use a try / catch in order to catch the potential error
        try
        {
            // The function returns a PDO object (PHP plugin used to connect to the databse)
            // This object has 3 mandatory arguments : the DSN, the username and the password
            // - the DSN (Data Source Name) which specifies the host, the dbname and the charset (the charset is optional)
            // - the username to connect to the database
            // - the password to connect to the database
            return new PDO('mysql:host=localhost;dbname=memory;charset=utf8', 'root', '');
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }
    }
}
