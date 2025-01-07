<?php
require_once('../src/models/projectModel.php');
require_once('../config/config.php');

class ProjectController {
    private $projectModel;

    // Constructor receives an instance of ProjectModel
    public function __construct(ProjectModel $projectModel) {
        $this->projectModel = $projectModel;
    }

    public function showAllProjects() {
        // Call the model method to fetch all projects
        $projects = $this->projectModel->getAllProjects();
    
        // Check if projects were found
        if (empty($projects)) {
            echo "No projects found.";
        } else {
            // Pass the data to the view
            $this->renderView('../views/homeManager.php', ['projects' => $projects]);
        }
    }

    // Helper method to render views
    private function renderView($viewPath, $data = []) {
        // Extract data into variables
        extract($data);

        // Include the view file
        include($viewPath);
    }
}