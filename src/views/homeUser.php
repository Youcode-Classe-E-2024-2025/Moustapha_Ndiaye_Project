<?php
// Enable error reporting for debugging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');
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
$taskModel = new TaskModel($pdo);


// Instantiate the controllers
$projectController = new ProjectController($projectModel);

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addProject'])) {
        $projectController->handleAddProject();
        header('Location: homeManager');
        exit;
    } elseif (isset($_POST['updateProject'])) {
        $projectController->handleUpdateProject();
        header('Location: homeManager');
        exit;
    } elseif (isset($_POST['deleteProject'])) {
        $projectController->handleDeleteProject();
        header('Location: homeManager');
        exit;
    } elseif (isset($_POST['addTask'])) {
        $taskController->handleAddTask();
        header('Location: homeManager');
        exit;
    } elseif (isset($_POST['updateTask'])) {
        $taskController->handleUpdateTask();
        header('Location: homeManager');
        exit;
    } elseif (isset($_POST['deleteTask'])) {
        $taskController->handleDeleteTask();
        header('Location: homeManager');
        exit;
    }
}

// Fetch data
$projectsUsers = $projectModel->getUserProjectDetails();
$UserTask = $projectModel->getUserTaskDetails();
// var_dump($UserTask);

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis-timeline-graph2d.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis-timeline-graph2d.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Project Manager</title>
</head>
<body class="bg-gradient-to-br from-blue-100 to-white p-8 min-h-screen">
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li><a href="#projects">Projects</a></li>
            <li><a href="#tasks">Tasks</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">

    <!-- Projects Section -->
    <div id="projects" class="section">
        <h2 class="">Projects</h2>
        <div class="max-w-4xl mx-auto">
            <!-- Project List -->
            <div class="project-list">
                <?php foreach ($projectsUsers as $project): ?>
                    <div class="project-card bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-xl font-bold mb-2"><?= htmlspecialchars($project['projectTitle'] ?? 'No title') ?></h2>
                        <p class="text-gray-700 mb-2"><strong>Description: </strong><?= htmlspecialchars($project['projectDescrip'] ?? 'No description') ?></p>
                        <p class="text-gray-700 mb-2"><strong>Category:</strong> <?= htmlspecialchars($project['category'] ?? 'No category') ?></p>
                        <p class="text-gray-700 mb-2"><strong>Start Date:</strong> <?= htmlspecialchars($project['startAt'] ?? 'No start date') ?></p>
                        <p class="text-gray-700 mb-2"><strong>End Date:</strong> <?= htmlspecialchars($project['endAt'] ?? 'No end date') ?></p>
                        <p class="text-gray-700 mb-4"><strong>Status:</strong> <?= htmlspecialchars($project['status'] ?? 'No status') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
    </div>
        <!-- Tasks Section -->
        <div id="tasks" class="section">
            <h2>Tasks</h2>
            <div class="max-w-4xl mx-auto">

                <!-- Task List -->
                <?php if (!empty($UserTask)) : ?>
                    <div class="task-list flex flex-wrap gap-3">
                        <?php foreach ($UserTask as $task) : ?>
                            <div class="task-card bg-white p-6 rounded-lg shadow-md mb-6">
                                <h2 class="text-xl font-bold mb-2"><?= htmlspecialchars($task['taskTitle'] ?? 'No title') ?></h2>
                                <p class="text-gray-700 mb-2"><strong>Description: </strong><?= htmlspecialchars($task['taskDescrip'] ?? 'No description') ?></p>
                                <p class="text-gray-700 mb-2"><strong>Start Date:</strong> <?= htmlspecialchars($task['startAt'] ?? 'No start date') ?></p>
                                <p class="text-gray-700 mb-2"><strong>End Date:</strong> <?= htmlspecialchars($task['endAt'] ?? 'No end date') ?></p>
                                <p class="text-gray-700 mb-2"><strong>Project:</strong> <?= htmlspecialchars($task['projectTitle'] ?? 'No project') ?></p>
                                <p class="text-gray-700 mb-2"><strong>Status:</strong> <?= htmlspecialchars($task['status'] ?? 'No status') ?></p>
                                <!-- Edit  -->
                                <div class="flex space-x-4">
                                    <button onclick="fillUpdateTaskForm(<?= htmlspecialchars(json_encode($task)) ?>); openModal('updateTaskModal')" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="text-gray-600">No tasks found!.</p>
                <?php endif; ?>
            </div>
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
                                <option value="Todo" <?= ($task['status'] ?? '') === 'Todo' ? 'selected' : '' ?>>Todo</option>
                                <option value="In Progress" <?= ($task['status'] ?? '') === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                <option value="Done" <?= ($task['status'] ?? '') === 'Done' ? 'selected' : '' ?>>Done</option>
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
    <script src="assets/js/main.js"></script>
</body>
</html>