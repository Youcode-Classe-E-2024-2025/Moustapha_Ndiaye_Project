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
        } 
    }

    


    public function handleAddProject() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addProject'])) {
            $projectTitle = $_POST['projectTitle'];
            $projectDescrip = $_POST['projectDescrip'];
            $category = $_POST['category'];
            $startAt = $_POST['startAt'];
            $endAt = $_POST['endAt'];
            $isPublic = $_POST['isPublic'];
            $status = $_POST['status'];
    
            // Ajouter le projet
            $success = $this->projectModel->addProject($projectTitle, $projectDescrip, $category, $startAt, $endAt, $isPublic, $status);
    
            if ($success) {
                echo "<script>alert('Projet ajouté avec succès !');</script>";
            } else {
                echo "<script>alert('Erreur lors de l'ajout du projet.');</script>";
            }
        }
    }

    public function handleUpdateProject() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProject'])) {
            $idProject = $_POST['idProject'];
            $projectTitle = $_POST['projectTitle'];
            $projectDescrip = $_POST['projectDescrip'];
            $category = $_POST['category'];
            $startAt = $_POST['startAt'];
            $endAt = $_POST['endAt'];
            $isPublic = $_POST['isPublic'];
            $status = $_POST['status'];
    
            // Mettre à jour le projet
            $success = $this->projectModel->updateProject($idProject, $projectTitle, $projectDescrip, $category, $startAt, $endAt, $isPublic, $status);
    
            if ($success) {
                echo "<script>alert('Projet mis à jour avec succès!');</script>";
            } else {
                echo "<script>alert('Erreur lors de la mise à jour du projet.');</script>";
            }
        }
    }
}