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
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/input.css">
    <link rel="stylesheet" href="assets/css/output.css">
</head>
<body class="bg-gradient-to-br from-blue-100 to-white min-h-screen flex items-center justify-center p-6">
    
        <div>i'm a user</div>
    <script src="assets/js/main.js"></script>
</body>
</html>