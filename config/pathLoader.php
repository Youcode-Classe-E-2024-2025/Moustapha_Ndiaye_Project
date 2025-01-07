<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // import require file
    require_once('../config/config.php') ;
    require_once('../config/loadDatabase.php');
    require_once('../src/controllers/authentificationController.php');

    // create database
    $db = new database() ;
    $pdo = $db->connexion();

    // load script database 
    $loader = new LoadDatabase($pdo, '../database/schemaDatabase.sql');
    $loader->fetchData();
    
?>