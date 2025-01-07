<?php
// homeManager.php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Import required files
require_once('../config/config.php');
require_once('../config/loadDatabase.php');
require_once('../src/models/projectModel.php');
require_once('../src/controllers/projectController.php');

// Create database connection
$db = new Database();
$pdo = $db->connexion();

// Load database schema (if needed)
$loader = new LoadDatabase($pdo, '../database/schemaDatabase.sql');
$loader->fetchData();

// Instantiate the model
$projectModel = new ProjectModel($pdo);

// Call the model method to fetch all projects
$projects = $projectModel->getAllProjects();

// Check if projects were found
if (empty($projects)) {
    echo "No projects found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
</head>
<body>
    <h1>Project Details</h1>
    <?php foreach ($projects as $project): ?>
        <div>
            <h2><?= htmlspecialchars($project['projectTitle'] ?? 'No title') ?></h2>
            <p><?= htmlspecialchars($project['projectDescrip'] ?? 'No description') ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($project['category'] ?? 'No category') ?></p>
            <p><strong>Start Date:</strong> <?= htmlspecialchars($project['startAt'] ?? 'No start date') ?></p>
            <p><strong>End Date:</strong> <?= htmlspecialchars($project['endAt'] ?? 'No end date') ?></p>
            <p><strong>Public:</strong> <?= $project['isPublic'] ? 'Yes' : 'No' ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($project['status'] ?? 'No status') ?></p>
        </div>
    <?php endforeach; ?>
</body>
</html>