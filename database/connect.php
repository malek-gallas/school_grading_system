<?php
    function connect(){
    require_once 'config.php';
        try {
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            $conn = new PDO($dsn, $user, $password, $options);
            return $conn ;
        } 
        catch (PDOException $e) {
            echo "Connection Failed: " . $e->getMessage();
            die();
        }
    }
?>