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
$taskModel = new TaskModel($pdo);
$userModel = new UserModel($pdo);

// Instantiate the controllers
$projectController = new ProjectController($projectModel);
$userController = new UserController($userModel);
$taskController = new TaskController($taskModel, $userModel, $projectModel);

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
$projects = $projectModel->getAllProjects();
$users = $userModel->getAllUsers();
$tasks = $taskModel->getAllTasks();

// Check if data is empty
if (empty($projects) && empty($users)) {
    echo "No projects or users found.";
    exit;
}

// Load database schema (if needed)
$loader = new LoadDatabase($pdo, '../database/schemaDatabase.sql');
$loader->fetchData();

// Prepare data for the timeline
$timelineData = [];
foreach ($projects as $project) {
    $timelineData[] = [
        'id' => $project['idProject'],
        'content' => htmlspecialchars($project['projectTitle']),
        'start' => $project['startAt'],
        'end' => $project['endAt'],
        'type' => 'project',
    ];
}
foreach ($tasks as $task) {
    $timelineData[] = [
        'id' => 'task_' . $task['taskId'],
        'content' => htmlspecialchars($task['taskTitle']),
        'start' => $task['startAt'],
        'end' => $task['endAt'],
        'type' => 'task',
    ];
}
$timelineJson = json_encode($timelineData);

// Prepare data for statistics
$taskStatusCount = [
    'Todo' => 0,
    'In Progress' => 0,
    'Done' => 0,
];
foreach ($tasks as $task) {
    if (isset($taskStatusCount[$task['status']])) {
        $taskStatusCount[$task['status']]++;
    }
}
$taskStatusJson = json_encode($taskStatusCount);

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
            <li><a href="#users">Users</a></li>
            <li><a href="#tasks">Tasks</a></li>
            <li><a href="#statistics">Statistics</a></li>
            <li><a href="#timeline">Timeline</a></li>
            <li><a href="#permissions">Permissions</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Projects Section -->
        <div id="projects" class="section">
            <h2>Projects</h2>
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
            <div class="flex flex-wrap gap-3 max-w-4xl mx-auto">
                <?php foreach ($users as $user) : ?>
                    <div class="user-card bg-white p-2 rounded-lg shadow-md">
                        <h2 class="text-xl font-bold"><?= htmlspecialchars($user['fullName'] ?? 'No name') ?></h2>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Tasks Section -->
        <div id="tasks" class="section">
            <h2>Tasks</h2>
            <div class="max-w-4xl mx-auto">
                <!-- Button to Open the Add Task Modal -->
                <button onclick="openModal('addTaskModal')" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-8">
                    Add Task
                </button>

                <!-- Add Task Modal -->
                <div id="addTaskModal" class="modal">
                    <div class="modal-content">
                        <h2 class="text-2xl font-bold mb-4">New Task</h2>
                        <form action="homeManager" method="POST" class="space-y-4">
                            <input type="hidden" id="taskId" name="taskId" value="">
                            <div>
                                <label for="taskTitle" class="block text-sm font-medium text-gray-700">Task Title:</label>
                                <input type="text" id="taskTitle" name="taskTitle" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="taskDescrip" class="block text-sm font-medium text-gray-700">Description:</label>
                                <textarea id="taskDescrip" name="taskDescrip" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"></textarea>
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
                                <label for="idProject" class="block text-sm font-medium text-gray-700">Project:</label>
                                <select id="idProject" name="idProject" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                    <option value="">Select a project</option>
                                    <?php if (!empty($projects)) : ?>
                                        <?php foreach ($projects as $project) : ?>
                                            <option value="<?= htmlspecialchars($project['idProject'] ?? '') ?>">
                                                <?= htmlspecialchars($project['projectTitle'] ?? '') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">No projects available</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status:</label>
                                <select id="status" name="status" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                    <option value="Todo">Todo</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Done">Done</option>
                                </select>
                            </div>
                            <div>
                                <label for="assignedTo" class="block text-sm font-medium text-gray-700">Assigned To:</label>
                                <select id="assignedTo" name="assignedTo" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                    <option value="">Select a user</option>
                                    <?php if (!empty($users)) : ?>
                                        <?php foreach ($users as $user) : ?>
                                            <option value="<?= htmlspecialchars($user['userId'] ?? '') ?>">
                                                <?= htmlspecialchars($user['fullName'] ?? '') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <option value="">No users available</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="flex justify-end space-x-4">
                                <button type="submit" name="addTask" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Add Task</button>
                                <button type="button" onclick="closeModal('addTaskModal')" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Task List -->
                <?php if (!empty($tasks)) : ?>
                    <div class="task-list flex flex-wrap gap-3">
                        <?php foreach ($tasks as $task) : ?>
                            <div class="task-card bg-white p-6 rounded-lg shadow-md mb-6">
                                <h2 class="text-xl font-bold mb-2"><?= htmlspecialchars($task['taskTitle'] ?? 'No title') ?></h2>
                                <p class="text-gray-700 mb-2"><strong>Description: </strong><?= htmlspecialchars($task['taskDescrip'] ?? 'No description') ?></p>
                                <p class="text-gray-700 mb-2"><strong>Start Date:</strong> <?= htmlspecialchars($task['startAt'] ?? 'No start date') ?></p>
                                <p class="text-gray-700 mb-2"><strong>End Date:</strong> <?= htmlspecialchars($task['endAt'] ?? 'No end date') ?></p>
                                <p class="text-gray-700 mb-2"><strong>Project:</strong> <?= htmlspecialchars($task['projectTitle'] ?? 'No project') ?></p>
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
        </div>

        <!-- Statistics Section -->
        <div id="statistics" class="section">
            <h2>Statistics</h2>
            <canvas id="taskStatusChart" width="400" height="200"></canvas>
            <script>
                const taskStatusData = <?= $taskStatusJson ?>;

                const ctx = document.getElementById('taskStatusChart').getContext('2d');
                const taskStatusChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(taskStatusData),
                        datasets: [{
                            label: 'Tasks by Status',
                            data: Object.values(taskStatusData),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(75, 192, 192, 1)',
                            ],
                            borderWidth: 1,
                        }],
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                            },
                        },
                    },
                });
            </script>
        </div>

        <!-- Timeline Section -->
        <div id="timeline" class="section">
            <h2>Timeline</h2>
            <div id="visualization" style="width: 100%; height: 400px;"></div>
            <script>
                // Récupérer les données de la timeline
                const timelineData = <?= $timelineJson ?>;

                // Convertir les données pour Vis.js
                const items = new vis.DataSet(timelineData);

                // Créer la timeline
                const container = document.getElementById('visualization');
                const options = {
                    showCurrentTime: true,
                    zoomable: true,
                    moveable: true,
                };
                const timeline = new vis.Timeline(container, items, options);
            </script>
        </div>

        <!-- Permissions Section -->
        <div id="permissions" class="section">
            <h2>Permissions</h2>
            <p>This is the Permissions section. Here you can view project permissions.</p>
        </div>
    </div>

    <!-- JavaScript for Modals -->
    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>