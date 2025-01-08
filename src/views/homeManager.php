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
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .update-form {
            display: none; /* Hide the update form by default */
        }
    </style>
    <script>
        // Function to show/hide the update form
        function toggleUpdateForm(projectId) {
            const form = document.getElementById(`update-form-${projectId}`);
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        // Function to confirm project deletion
        function confirmDelete(projectId) {
            return confirm(`Are you sure you want to delete project ${projectId}?`);
        }
    </script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Add Project Form -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-bold mb-4">Add a New Project</h2>
            <form action="../controllers/ProjectController.php" method="POST" class="space-y-4">
                <div>
                    <label for="projectTitle" class="block text-sm font-medium text-gray-700">Project Title:</label>
                    <input type="text" id="projectTitle" name="projectTitle" required
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="projectDescrip" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea id="projectDescrip" name="projectDescrip" required
                              class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                    <input type="text" id="category" name="category" required
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="startAt" class="block text-sm font-medium text-gray-700">Start Date:</label>
                    <input type="date" id="startAt" name="startAt" required
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="endAt" class="block text-sm font-medium text-gray-700">End Date:</label>
                    <input type="date" id="endAt" name="endAt" required
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="isPublic" class="block text-sm font-medium text-gray-700">Public:</label>
                    <select id="isPublic" name="isPublic" required
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status:</label>
                    <input type="text" id="status" name="status" required
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                </div>
                <button type="submit" name="addProject"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Add Project
                </button>
            </form>
        </div>

        <!-- List of Projects -->
        <h1 class="text-3xl font-bold mb-6">Project List</h1>
        <?php foreach ($projects as $project): ?>
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-xl font-bold mb-2"><?= htmlspecialchars($project['projectTitle'] ?? 'No title') ?></h2>
                <p class="text-gray-700 mb-2"><strong>Description: </strong><?= htmlspecialchars($project['projectDescrip'] ?? 'No description') ?></p>
                <p class="text-gray-700 mb-2"><strong>Category:</strong> <?= htmlspecialchars($project['category'] ?? 'No category') ?></p>
                <p class="text-gray-700 mb-2"><strong>Start Date:</strong> <?= htmlspecialchars($project['startAt'] ?? 'No start date') ?></p>
                <p class="text-gray-700 mb-2"><strong>End Date:</strong> <?= htmlspecialchars($project['endAt'] ?? 'No end date') ?></p>
                <p class="text-gray-700 mb-2"><strong>Public:</strong> <?= $project['isPublic'] ? 'Yes' : 'No' ?></p>
                <p class="text-gray-700 mb-4"><strong>Status:</strong> <?= htmlspecialchars($project['status'] ?? 'No status') ?></p>

                <!-- Edit and Delete Buttons -->
                <div class="flex space-x-4">
                    <button onclick="toggleUpdateForm(<?= $project['idProject'] ?>)"
                            class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                        Edit
                    </button>
                    <form action="../controllers/ProjectController.php" method="POST" onsubmit="return confirmDelete(<?= $project['idProject'] ?>);">
                        <input type="hidden" name="idProject" value="<?= $project['idProject'] ?>">
                        <button type="submit" name="deleteProject"
                                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                            Delete
                        </button>
                    </form>
                </div>

                <!-- Update Form (hidden by default) -->
                <div id="update-form-<?= $project['idProject'] ?>" class="update-form mt-6">
                    <h3 class="text-lg font-bold mb-4">Edit Project</h3>
                    <form action="../controllers/ProjectController.php" method="POST" class="space-y-4">
                        <input type="hidden" name="idProject" value="<?= $project['idProject'] ?>">
                        <div>
                            <label for="projectTitle" class="block text-sm font-medium text-gray-700">Project Title:</label>
                            <input type="text" id="projectTitle" name="projectTitle" value="<?= $project['projectTitle'] ?>" required
                                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="projectDescrip" class="block text-sm font-medium text-gray-700">Description:</label>
                            <textarea id="projectDescrip" name="projectDescrip" required
                                      class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"><?= $project['projectDescrip'] ?></textarea>
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                            <input type="text" id="category" name="category" value="<?= $project['category'] ?>" required
                                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="startAt" class="block text-sm font-medium text-gray-700">Start Date:</label>
                            <input type="date" id="startAt" name="startAt" value="<?= $project['startAt'] ?>" required
                                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="endAt" class="block text-sm font-medium text-gray-700">End Date:</label>
                            <input type="date" id="endAt" name="endAt" value="<?= $project['endAt'] ?>" required
                                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="isPublic" class="block text-sm font-medium text-gray-700">Public:</label>
                            <select id="isPublic" name="isPublic" required
                                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                <option value="1" <?= $project['isPublic'] ? 'selected' : '' ?>>Yes</option>
                                <option value="0" <?= !$project['isPublic'] ? 'selected' : '' ?>>No</option>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status:</label>
                            <input type="text" id="status" name="status" value="<?= $project['status'] ?>" required
                                   class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <button type="submit" name="updateProject"
                                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                            Update Project
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>