<?php
// homeManager

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



// Instantiate the model
$projectModel = new ProjectModel($pdo);
// $userModel = new UserModel($pdo);
// $taskModel = new TaskModel($pdo);
$taskModel = new TaskModel($pdo); // Supposons que $pdo est une instance de PDO
$userModel = new UserModel($pdo);


// Instantiate the controller with the model
$projectController = new ProjectController($projectModel);
$userController = new UserController($userModel) ;
// $taskController = new TaskController($taskModel); 
$taskController = new TaskController($taskModel, $userModel, $projectModel);


// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addProject'])) {
        $projectController->handleAddProject();
        header('Location: homeManager'); // Redirect after adding
        exit;
    } elseif (isset($_POST['updateProject'])) {
        $projectController->handleUpdateProject();
        header('Location: homeManager'); // Redirect after updating
        exit;
    } elseif (isset($_POST['deleteProject'])) {
        $projectController->handleDeleteProject();
        header('Location: homeManager'); // Redirect after deleting
        exit;
    }
}

// Display all projects
$projectController->showAllProjects();
$projects = $projectModel->getAllProjects();

// display all users
$userController->showAllUsers();
$users = $userModel-> getAllUsers();

// display all atsk
$taskController->showAllTasks(); 
$tasks = $taskModel->getAllTasks();
// var_dump($tasks);

// Gérer les actions (ajout, mise à jour, suppression)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addTask'])) {
        $taskController->handleAddTask();
    } elseif (isset($_POST['updateTask'])) {
        $taskController->handleUpdateTask();
    } elseif (isset($_POST['deleteTask'])) {
        $taskController->handleDeleteTask();
    }
}

// Récupérer les tâches pour affichage
// $tasks = $taskController->showAllTasks();

// Check if projects were found
if (empty($projects || $users)) {
    echo "No found.";
    exit;
}
// Load database schema (if needed)
$loader = new LoadDatabase($pdo, '../database/schemaDatabase.sql');
$loader->fetchData();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/input.css">
    <link rel="stylesheet" href="assets/css/output.css">
    
</head>
<body class="bg-gradient-to-br from-blue-100 to-white p-8 min-h-screen">
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><a href="#projects">Projects</a></li>
            <li><a href="#users">Users</a></li>
            <li><a href="#tasks">Tasks</a></li>
            <li><a href="#statistics">Statistics</a></li>
            <li><a href="#statistics">Persmissions</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Projects Section -->
        <div id="projects" class="section">
            <h2>Projects</h2>
            <!-- Add your projects content here -->

            <div class="max-w-4xl mx-auto">
                <!-- Button to Open the Add Project Modal -->
                <button onclick="openModal('addProjectModal')" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-8">
                    Add Project
                </button>

                <!-- Add Project Modal -->
                <div id="addProjectModal" class="modal">
                    <div class="modal-content">
                        <h2 class="text-2xl font-bold mb-4">New Project</h2>
                        <form action="homeManager" method="POST" class="space-y-4">
                            <div>
                                <label for="projectTitle" class="block text-sm font-medium text-gray-700">Project Title:</label>
                                <input type="text" id="projectTitle" name="projectTitle" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="projectDescrip" class="block text-sm font-medium text-gray-700">Description:</label>
                                <textarea id="projectDescrip" name="projectDescrip" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                                <input type="text" id="category" name="category" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="startAt" class="block text-sm font-medium text-gray-700">Start Date:</label>
                                <input type="date" id="startAt" name="startAt" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="endAt" class="block text-sm font-medium text-gray-700">End Date:</label>
                                <input type="date" id="endAt" name="endAt" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="isPublic" class="block text-sm font-medium text-gray-700">Public:</label>
                                <select id="isPublic" name="isPublic" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status:</label>
                                <input type="text" id="status" name="status" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <button type="submit" name="addProject" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Add Project</button>
                            <button type="button" onclick="closeModal('addProjectModal')" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                        </form>
                    </div>
                </div>

                <!-- Project List -->
                <div class="project-list">
                    <?php foreach ($projects as $project): ?>
                        <div class="project-card bg-white p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-bold mb-2"><?= htmlspecialchars($project['projectTitle'] ?? 'No title') ?></h2>
                            <p class="text-gray-700 mb-2"><strong>Description: </strong><?= htmlspecialchars($project['projectDescrip'] ?? 'No description') ?></p>
                            <p class="text-gray-700 mb-2"><strong>Category:</strong> <?= htmlspecialchars($project['category'] ?? 'No category') ?></p>
                            <p class="text-gray-700 mb-2"><strong>Start Date:</strong> <?= htmlspecialchars($project['startAt'] ?? 'No start date') ?></p>
                            <p class="text-gray-700 mb-2"><strong>End Date:</strong> <?= htmlspecialchars($project['endAt'] ?? 'No end date') ?></p>
                            <p class="text-gray-700 mb-2"><strong>Public:</strong> <?= $project['isPublic'] ? 'Yes' : 'No' ?></p>
                            <p class="text-gray-700 mb-4"><strong>Status:</strong> <?= htmlspecialchars($project['status'] ?? 'No status') ?></p>

                            <!-- Edit and Delete Buttons -->
                            <div class="flex space-x-4">
                                <button onclick="fillUpdateForm(<?= htmlspecialchars(json_encode($project)) ?>); openModal('updateProjectModal')" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                                    Edit
                                </button>
                                <form action="homeManager" method="POST" onsubmit="return confirmDelete(<?= $project['idProject'] ?>)">
                                    <input type="hidden" name="idProject" value="<?= $project['idProject'] ?>">
                                    <button type="submit" name="deleteProject" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Update Project Modal -->
                <div id="updateProjectModal" class="modal">
                    <div class="modal-content">
                        <h3 class="text-lg font-bold mb-4">Edit Project</h3>
                        <form action="homeManager" method="POST" class="space-y-4">
                            <input type="hidden" id="updateidProject" name="idProject">
                            <div>
                                <label for="updateProjectTitle" class="block text-sm font-medium text-gray-700">Project Title:</label>
                                <input type="text" id="updateProjectTitle" name="projectTitle" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="updateProjectDescrip" class="block text-sm font-medium text-gray-700">Description:</label>
                                <textarea id="updateProjectDescrip" name="projectDescrip" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                            <div>
                                <label for="updateCategory" class="block text-sm font-medium text-gray-700">Category:</label>
                                <input type="text" id="updateCategory" name="category" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="updateStartAt" class="block text-sm font-medium text-gray-700">Start Date:</label>
                                <input type="date" id="updateStartAt" name="startAt" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="updateEndAt" class="block text-sm font-medium text-gray-700">End Date:</label>
                                <input type="date" id="updateEndAt" name="endAt" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="updateIsPublic" class="block text-sm font-medium text-gray-700">Public:</label>
                                <select id="updateIsPublic" name="isPublic" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div>
                                <label for="updateStatus" class="block text-sm font-medium text-gray-700">Status:</label>
                                <input type="text" id="updateStatus" name="status" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <button type="submit" name="updateProject" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Update Project</button>
                            <button type="button" onclick="closeModal('updateProjectModal')" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Section -->
        <div id="users" class="section">
        <h2>Users</h2>
            <!-- Liste des utilisateurs -->
            <div class="flex flex-wrap gap-3">
                <?php foreach ($users as $user) : ?>
                    <div class="user-card bg-white p-2 rounded-lg shadow-md">
                        <h2 class="text-xl font-bold"><?= htmlspecialchars($user['fullName'] ?? 'No name') ?></h2>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

       <!-- Afficher les tâches dans la vue -->
<div id="tasks" class="section">
    <h2 class="text-2xl font-bold mb-4">Tasks</h2>
    <?php if (!empty($tasks)) : ?>
        <div class="task-list">
            <?php foreach ($tasks as $task) : ?>
                <div class="task-card bg-white p-6 rounded-lg shadow-md mb-6">
                    <h2 class="text-xl font-bold mb-2"><?= htmlspecialchars($task['taskTitle'] ?? 'No title') ?></h2>
                    <p class="text-gray-700 mb-2"><strong>Description: </strong><?= htmlspecialchars($task['taskDescrip'] ?? 'No description') ?></p>
                    <p class="text-gray-700 mb-2"><strong>Start Date:</strong> <?= htmlspecialchars($task['startAt'] ?? 'No start date') ?></p>
                    <p class="text-gray-700 mb-2"><strong>End Date:</strong> <?= htmlspecialchars($task['endAt'] ?? 'No end date') ?></p>
                    <p class="text-gray-700 mb-2"><strong>Project:</strong> <?= htmlspecialchars($task['projectTitle'] ?? 'No project') ?></p>  <!-- Afficher le projectTitle -->
                    <p class="text-gray-700 mb-2"><strong>Status:</strong> <?= htmlspecialchars($task['status'] ?? 'No status') ?></p>
                    <p class="text-gray-700 mb-4">
                        <strong>Assigned To:</strong>
                        <?= htmlspecialchars($task['assignedUserName'] ?? 'Not assigned') ?>
                    </p>

                    <!-- Edit and Delete Buttons -->
                    <div class="flex space-x-4">
                        <button onclick="fillUpdateTaskForm(<?= htmlspecialchars(json_encode($task)) ?>); openModal('updateTaskModal')" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                            Edit
                        </button>
                        <form action="homeManager" method="POST" onsubmit="return confirmDeleteTask(<?= $task['taskId'] ?>)">
                            <input type="hidden" name="taskId" value="<?= $task['taskId'] ?>">
                            <button type="submit" name="deleteTask" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p class="text-gray-600">No tasks found.</p>
    <?php endif; ?>
</div>

<!-- Update Task Modal -->
<div id="updateTaskModal" class="modal">
    <div class="modal-content">
        <h3 class="text-lg font-bold mb-4">Edit Task</h3>
        <form action="homeManager" method="POST" class="space-y-4">
            <input type="hidden" id="updateTaskId" name="taskId" value="<?= htmlspecialchars($task['taskId'] ?? '') ?>">
            
            <div>
                <label for="updateTaskTitle" class="block text-sm font-medium text-gray-700">Task Title:</label>
                <input type="text" id="updateTaskTitle" name="taskTitle" value="<?= htmlspecialchars($task['taskTitle'] ?? '') ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            
            <div>
                <label for="updateTaskDescrip" class="block text-sm font-medium text-gray-700">Description:</label>
                <textarea id="updateTaskDescrip" name="taskDescrip" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"><?= htmlspecialchars($task['taskDescrip'] ?? '') ?></textarea>
            </div>
            
            <div>
                <label for="updateStartAt" class="block text-sm font-medium text-gray-700">Start Date:</label>
                <input type="date" id="updateStartAt" name="startAt" value="<?= htmlspecialchars($task['startAt'] ?? '') ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            
            <div>
                <label for="updateEndAt" class="block text-sm font-medium text-gray-700">End Date:</label>
                <input type="date" id="updateEndAt" name="endAt" value="<?= htmlspecialchars($task['endAt'] ?? '') ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
            </div>
            
            <div>
                <label for="updateIdProject" class="block text-sm font-medium text-gray-700">Project:</label>
                <select id="updateIdProject" name="idProject" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="">Select a project</option>
                    <?php foreach ($projects as $project) : ?>
                        <option value="<?= htmlspecialchars($project['idProject'] ?? '') ?>" <?= ($task['idProject'] ?? '') == $project['idProject'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($project['projectTitle'] ?? '') ?>  <!-- Afficher uniquement le projectTitle -->
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="updateStatus" class="block text-sm font-medium text-gray-700">Status:</label>
                <select id="updateStatus" name="status" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="Pending" <?= ($task['status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="In Progress" <?= ($task['status'] ?? '') === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Completed" <?= ($task['status'] ?? '') === 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
            
            <div>
                <label for="updateAssignedTo" class="block text-sm font-medium text-gray-700">Assigned To:</label>
                <select id="updateAssignedTo" name="assignedTo" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="">Select a user</option>
                    <?php foreach ($users as $user) : ?>
                        <option value="<?= htmlspecialchars($user['userId'] ?? '') ?>" <?= ($task['assignedTo'] ?? '') == $user['userId'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($user['fullName'] ?? '') ?>  <!-- Afficher uniquement le fullName -->
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" name="updateTask" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Update Task</button>
            <button type="button" onclick="closeModal('updateTaskModal')" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
        </form>
    </div>
</div>
        <!-- Statistics Section -->
        <div id="statistics" class="section">
            <h2>Statistics</h2>
            <p>This is the Statistics section. Here you can view project statistics.</p>
            <!-- Add your statistics content here -->
        </div>

        <!-- Statistics Section -->
        <div id="statistics" class="section">
            <h2>Persmissions</h2>
            <p>This is the Persmissions section. Here you can view project Persmissions.</p>
            <!-- Add your statistics content here -->
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>