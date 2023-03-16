<?php

    try{
        $database = new PDO("mysql:host=localhost;dbname=projetr401",'root','');

    }catch(PDOException $e){
        die('Erreur : '.$e->getMessage());
    }


?>