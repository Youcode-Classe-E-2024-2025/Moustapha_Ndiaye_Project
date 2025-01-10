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

    public function showPublicProjects() {
        $publicProjects = $this->projectModel->getPublicProjects();
        if (empty($publicProjects)) {
            echo "No projects found.";
        } 
    }

    public function getUserProjectDetail(){
        $getUserProjectDetails = $this->getUserProjectDetails();
        if (empty($getUserProjectDetails)) {
            echo "No projects or users found.";
        } 
    }

    public function getUserTaskDetail(){
        $getUserTaskDetails = $this->getUserTaskDetails();
        if (empty($getUserTaskDetails)) {
            echo "No task or users found.";
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
    
            $success = $this->projectModel->updateProject($idProject, $projectTitle, $projectDescrip, $category, $startAt, $endAt, $isPublic, $status);
    
            if ($success) {
                echo "<script>alert('Projet mis à jour avec succès!');</script>";
            } else {
                echo "<script>alert('Erreur lors de la mise à jour du projet.');</script>";
            }
        }
    }

    public function handleDeleteProject() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteProject'])) {
            $idProject = $_POST['idProject'];
    
            $success = $this->projectModel->deleteProject($idProject);
    
            if ($success) {
                echo "<script>alert('Projet supprimé avec succès!');</script>";
            } else {
                echo "<script>alert('Erreur lors de la suppression du projet..');</script>";
            }
        }
    }
}

class UserController {
    private $UserModel ;

    public function __construct(UserModel $UserModel){
        $this->UserModel = $UserModel ;
    }

    public function showAllUsers(){
        $users = $this->UserModel->getAllUsers();

        if(empty($users)){
            echo "<script>alert('No users found')</script>";
        }
    }
}

class TaskController {
    private $TaskModel;
    private $UserModel;
    private $ProjectModel;


    public function __construct(TaskModel $TaskModel, UserModel $UserModel, ProjectModel $ProjectModel) {
        $this->TaskModel = $TaskModel;
        $this->UserModel = $UserModel; 
        $this->ProjectModel = $ProjectModel;
    }

    public function showAllTasks() {
        // Récupérer les tâches, les utilisateurs et les projets
        $tasks = $this->TaskModel->getAllTasks();
        $users = $this->UserModel->getAllUsers();
        $projects = $this->ProjectModel->getAllProjects();
    
        // Vérifier si des tâches ont été trouvées
        if (empty($tasks)) {
            echo "<script>alert('No tasks found')</script>";
            return ['tasks' => [], 'users' => $users, 'projects' => $projects];
        }
    
        // Retourner les données
        return ['tasks' => $tasks, 'users' => $users, 'projects' => $projects];
    }

    public function handleAddTask() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTask'])) {
            $taskTitle = $_POST['taskTitle'];
            $taskDescrip = $_POST['taskDescrip'];
            $startAt = $_POST['startAt'];
            $endAt = $_POST['endAt'];
            $idProject = $_POST['idProject'];
            $status = $_POST['status'];
            $assignedTo = $_POST['assignedTo'];
    
            if (empty($taskTitle) || empty($taskDescrip) || empty($startAt) || empty($endAt) || empty($idProject) || empty($status) || empty($assignedTo)) {
                echo "<script>alert('All fields are required.')</script>";
                return;
            }
    
            $taskId = $this->TaskModel->addTask($taskTitle, $taskDescrip, $startAt, $endAt, $idProject, $status, $assignedTo);
    
            if ($taskId) {
                echo "<script>alert('Task added successfully!')</script>";
            } else {
                echo "<script>alert('Failed to add task.')</script>";
            }
        }
    }

    public function handleUpdateTask() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateTask'])) {
            try {
                $taskId = $_POST['taskId'] ?? null;
                $taskTitle = htmlspecialchars($_POST['taskTitle'] ?? '');
                $taskDescrip = htmlspecialchars($_POST['taskDescrip'] ?? '');
                $startAt = $_POST['startAt'] ?? '';
                $endAt = $_POST['endAt'] ?? '';
                $idProject = $_POST['idProject'] ?? null;
                $status = htmlspecialchars($_POST['status'] ?? '');
                $assignedTo = $_POST['assignedTo'] ?? null;
    
                if ($idProject === '') {
                    $idProject = null; 
                } elseif ($idProject !== null && !is_numeric($idProject)) {
                    throw new InvalidArgumentException("idProject doit être un entier valide ou null.");
                } else {
                    $idProject = (int)$idProject; 
                }
    
                if (empty($taskId) || empty($taskTitle) || empty($taskDescrip) || empty($startAt) || empty($endAt) || empty($status)) {
                    throw new InvalidArgumentException("Tous les champs obligatoires doivent être remplis.");
                }
    
                if (!is_numeric($taskId)) {
                    throw new InvalidArgumentException("taskId doit être un entier valide.");
                }
    
                if ($assignedTo !== null && !is_numeric($assignedTo)) {
                    throw new InvalidArgumentException("assignedTo doit être un entier valide ou null.");
                }
    
                if (!strtotime($startAt) || !strtotime($endAt)) {
                    throw new InvalidArgumentException("Les dates de début et de fin doivent être au format valide (YYYY-MM-DD).");
                }
    
                $rowsAffected = $this->TaskModel->updateTask($taskId, $taskTitle, $taskDescrip, $startAt, $endAt, $idProject, $status, $assignedTo);
    
                if ($rowsAffected > 0) {
                    echo "<script>alert('Tâche mise à jour avec succès !')</script>";
                } else {
                    throw new Exception("Aucune tâche n'a été mise à jour.");
                }
            } catch (InvalidArgumentException $e) {
                error_log("Erreur de validation : " . $e->getMessage());
                echo "<script>alert('Erreur : " . addslashes($e->getMessage()) . "')</script>";
            } catch (Exception $e) {
                error_log("Erreur lors de la mise à jour de la tâche : " . $e->getMessage());
                echo "<script>alert('Erreur : " . addslashes($e->getMessage()) . "')</script>";
            }
        }
    }
    
    public function handleDeleteTask() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteTask'])) {
            $taskId = $_POST['taskId'];
    
            if (empty($taskId)) {
                echo "<script>alert('Task ID is required.')</script>";
                return;
            }
    
            $rowsAffected = $this->TaskModel->deleteTask($taskId);
    
            if ($rowsAffected > 0) {
                echo "<script>alert('Task deleted successfully!')</script>";
            } else {
                echo "<script>alert('Failed to delete task.')</script>";
            }
        }
    }
}