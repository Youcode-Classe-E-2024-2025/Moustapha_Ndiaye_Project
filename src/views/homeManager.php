
<?php
    require_once('../config/pathLoader.php') ;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/input.css">
    <link rel="stylesheet" href="assets/css/output.css">
    <!-- Include Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
</head>
<body class="bg-gradient-to-br from-blue-100 to-white ">
    <div class="flex">
        <!-- Sidebar -->
        <div class="bg-blue-600 text-white w-100 min-h-screen p-6">
            <h1 class="text-2xl font-bold mb-6">Dashbord Management</h1>
            <ul>
                <!-- Link to Projects -->
                <li class="mb-4">
                    <a href="#projects" class="flex items-center p-2 hover:bg-gray-700 rounded sidebar-link">
                        <span class="ml-2">Projects</span>
                    </a>
                </li>
                <!-- Link to Users -->
                <li class="mb-4">
                    <a href="#users" class="flex items-center p-2 hover:bg-gray-700 rounded sidebar-link">
                        <span class="ml-2">Users</span>
                    </a>
                </li>
                
                <!-- Link to Tasks -->
                <li class="mb-4">
                    <a href="#tasks" class="flex items-center p-2 hover:bg-gray-700 rounded sidebar-link">
                        <span class="ml-2">Tasks</span>
                    </a>
                </li>
                <!-- Link to Statistics -->
                <li class="mb-4">
                    <a href="#statistics" class="flex items-center p-2 hover:bg-gray-700 rounded sidebar-link">
                        <span class="ml-2">Statistics</span>
                    </a>
                </li>
                <!-- Link to Logout -->
                <li class="mb-4">
                    <a href="#logout" class="flex items-center p-2 hover:bg-gray-700 rounded sidebar-link">
                        <span class="ml-2">Logout</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
              <!-- Projects Section -->
              <div id="projects" class="content-section hidden">
                <h2 class="text-3xl font-bold mb-6">Projects</h2>
                <div class="bg-white p-6 rounded-lg shadow">
                    
                </div>
            </div>
            <!-- Users Section -->
            <div id="users" class="content-section">
                <h2 class="text-3xl font-bold mb-6">Users</h2>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p>Manage users here. Add, edit, or remove users from the system.</p>
                </div>
            </div>

          

            <!-- Tasks Section -->
            <div id="tasks" class="content-section hidden">
                <h2 class="text-3xl font-bold mb-6">Tasks</h2>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold mb-4">Task List</h3>
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="text-left py-2">Task</th>
                                <th class="text-left py-2">Status</th>
                                <th class="text-left py-2">Due Date</th>
                                <th class="text-left py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example Task -->
                            <tr class="border-b">
                                <td class="py-2">Team Meeting</td>
                                <td class="py-2"><span class="bg-green-200 text-green-800 px-2 py-1 rounded">Completed</span></td>
                                <td class="py-2">2023-10-15</td>
                                <td class="py-2">
                                    <button class="text-blue-500 hover:text-blue-700">Edit</button>
                                    <button class="text-red-500 hover:text-red-700 ml-2">Delete</button>
                                </td>
                            </tr>
                            <!-- Add more tasks here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statistics Section -->
            <div id="statistics" class="content-section hidden">
                <h2 class="text-3xl font-bold mb-6">Statistics</h2>
                <!-- Grid for Charts -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Doughnut Chart for Task Status -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <canvas id="taskStatusChart"></canvas>
                    </div>
                    <!-- Line Chart for Task Completion -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <canvas id="taskCompletionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Logout Section -->
            <div id="logout" class="content-section hidden">
                <h2 class="text-3xl font-bold mb-6">Logout</h2>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p>Are you sure you want to log out? <a href="#" class="text-blue-500 hover:text-blue-700">Confirm Logout</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script to handle navigation between sections
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault(); // Prevent default link behavior
                const targetId = link.getAttribute('href').substring(1); // Get the target section ID
                // Hide all sections
                document.querySelectorAll('.content-section').forEach(section => {
                    section.classList.add('hidden');
                });
                // Show the target section
                document.getElementById(targetId).classList.remove('hidden');
            });
        });

        // Configuration for the Task Status Doughnut Chart
        const taskStatusChart = new Chart(document.getElementById('taskStatusChart'), {
            type: 'doughnut', // Chart type
            data: {
                labels: ['Completed', 'In Progress', 'Overdue'], // Data labels
                datasets: [{
                    label: 'Task Status',
                    data: [45, 12, 3], // Data values
                    backgroundColor: ['#4ade80', '#fbbf24', '#f87171'], // Segment colors
                }]
            },
            options: {
                responsive: true, // Make the chart responsive
                plugins: {
                    legend: {
                        position: 'top', // Legend position
                    },
                }
            }
        });

        // Configuration for the Task Completion Line Chart
        const taskCompletionChart = new Chart(document.getElementById('taskCompletionChart'), {
            type: 'line', // Chart type
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], // Data labels
                datasets: [{
                    label: 'Completed Tasks',
                    data: [10, 20, 15, 30], // Data values
                    borderColor: '#3b82f6', // Line color
                    fill: false, // Do not fill under the line
                }]
            },
            options: {
                responsive: true, // Make the chart responsive
                plugins: {
                    legend: {
                        position: 'top', // Legend position
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true // Start the Y-axis at 0
                    }
                }
            }
        });
    </script>
</body>
</html>