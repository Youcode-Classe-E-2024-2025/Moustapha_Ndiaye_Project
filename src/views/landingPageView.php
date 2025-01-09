<?php
// Enable error reporting for debugging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import required files
require_once('../config/config.php');
require_once('../config/loadDatabase.php');
require_once('../src/models/projectModel.php');
require_once('../src/controllers/projectController.php');

// Create database connection
$db = new Database();
$pdo = $db->connexion();

// Instantiate the models
$projectModel = new ProjectModel($pdo);

// Instantiate the controllers
$projectController = new ProjectController($projectModel);

// Fetch public projects
$projects = $projectModel->getPublicProjects();

// Check if data is empty
if (empty($projects)) {
    echo "No public projects found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/output.css">
    <title>Landing Page</title>
</head>
<body class="bg-gradient-to-br from-blue-100 to-white p-8 min-h-screen">
    <!-- Header -->
    <header class="text-center mb-8">
        <h1 class="text-3xl font-bold">Welcome to Our Project Manager</h1>
        <a href="loginView" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
            Get Started
        </a>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Public Projects Section -->
        <div id="projects" class="section">
            
            <div class="max-w-3xl mx-auto">
                <!-- Project List -->
                <div class="project-list grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card bg-white p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-bold mb-2"><?= htmlspecialchars($project['projectTitle'] ?? 'No title') ?></h2>
                            <p class="text-gray-700 mb-2"><strong>Description: </strong><?= htmlspecialchars($project['projectDescrip'] ?? 'No description') ?></p>
                            </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>