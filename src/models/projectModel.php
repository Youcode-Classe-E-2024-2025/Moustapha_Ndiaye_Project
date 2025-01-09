<?php

class ProjectModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
    public function getAllProjects() {
        $sql = "SELECT idProject, projectTitle, projectDescrip, category, startAt, endAt, isPublic, status FROM Project";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublicProjects() {
        $sql = "SELECT idProject, projectTitle, projectDescrip, isPublic, status FROM Project WHERE isPublic = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProject($projectTitle, $projectDescrip, $category, $startAt, $endAt, $isPublic, $status) {
        $sql = "INSERT INTO Project (projectTitle, projectDescrip, category, startAt, endAt, isPublic, status)
                VALUES (:projectTitle, :projectDescrip, :category, :startAt, :endAt, :isPublic, :status)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'projectTitle' => $projectTitle,
            'projectDescrip' => $projectDescrip,
            'category' => $category,
            'startAt' => $startAt,
            'endAt' => $endAt,
            'isPublic' => $isPublic,
            'status' => $status
        ]);
        return $stmt->rowCount() > 0; 
    }

    public function updateProject($idProject, $projectTitle, $projectDescrip, $category, $startAt, $endAt, $isPublic, $status) {
        $sql = "UPDATE Project
                SET projectTitle = :projectTitle,
                    projectDescrip = :projectDescrip,
                    category = :category,
                    startAt = :startAt,
                    endAt = :endAt,
                    isPublic = :isPublic,
                    status = :status
                WHERE idProject = :idProject";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'idProject' => $idProject,
            'projectTitle' => $projectTitle,
            'projectDescrip' => $projectDescrip,
            'category' => $category,
            'startAt' => $startAt,
            'endAt' => $endAt,
            'isPublic' => $isPublic,
            'status' => $status
        ]);
        return $stmt->rowCount() > 0; 
    }

    public function deleteProject($idProject) {
        $sql = "DELETE FROM Project WHERE idProject = :idProject";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['idProject' => $idProject]);
        return $stmt->rowCount() > 0; 
    }
}


class UserModel {
    private $pdo ;

    public function __construct($pdo){
        $this->pdo = $pdo ;
    }


    public function getAllUsers() {
        $sql = "SELECT userId, fullName FROM User WHERE role = 'TeamMember'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

class TaskModel {
    private $pdo ;

    public function __construct($pdo){
        $this->pdo = $pdo ;
    }

  
    public function getAllTasks() {
        $sql = "
            SELECT 
                Task.taskId, 
                Task.taskTitle, 
                Task.taskDescrip, 
                Task.startAt, 
                Task.endAt, 
                Task.idProject, 
                Task.status, 
                Task.assignedTo, 
                User.fullName AS assignedUserName,
                Project.projectTitle AS projectTitle  
            FROM Task
            LEFT JOIN User ON Task.assignedTo = User.userId
            LEFT JOIN Project ON Task.idProject = Project.idProject  
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addTask($taskTitle, $taskDescrip, $startAt, $endAt, $idProject, $status, $assignedTo) {
        $sql = "
            INSERT INTO Task (taskTitle, taskDescrip, startAt, endAt, idProject, status, assignedTo)
            VALUES (:taskTitle, :taskDescrip, :startAt, :endAt, :idProject, :status, :assignedTo)
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'taskTitle' => $taskTitle,
            'taskDescrip' => $taskDescrip,
            'startAt' => $startAt,
            'endAt' => $endAt,
            'idProject' => $idProject,
            'status' => $status,
            'assignedTo' => $assignedTo
        ]);
        return $this->pdo->lastInsertId();
    }

    public function updateTask($taskId, $taskTitle, $taskDescrip, $startAt, $endAt, $idProject, $status, $assignedTo) {
        // Validation des entrées
        if (!is_numeric($taskId)) {
            throw new InvalidArgumentException("taskId doit être un entier valide.");
        }
    
        if ($idProject !== null && !is_numeric($idProject)) {
            error_log("Valeur invalide pour idProject : " . print_r($idProject, true));
            throw new InvalidArgumentException("idProject doit être un entier valide ou null.");
        }
    
        if ($assignedTo !== null && !is_numeric($assignedTo)) {
            error_log("Valeur invalide pour assignedTo : " . print_r($assignedTo, true));
            throw new InvalidArgumentException("assignedTo doit être un entier valide ou null.");
        }
    
        // Validation des dates
        if (!strtotime($startAt) || !strtotime($endAt)) {
            throw new InvalidArgumentException("Les dates de début et de fin doivent être au format valide (YYYY-MM-DD).");
        }
    
        // Construction de la requête SQL
        $sql = "
            UPDATE Task
            SET taskTitle = :taskTitle,
                taskDescrip = :taskDescrip,
                startAt = :startAt,
                endAt = :endAt,
                idProject = :idProject,
                status = :status,
                assignedTo = :assignedTo
            WHERE taskId = :taskId
        ";
    
        // Préparation et exécution de la requête
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'taskId' => (int)$taskId,
                'taskTitle' => $taskTitle,
                'taskDescrip' => $taskDescrip,
                'startAt' => $startAt,
                'endAt' => $endAt,
                'idProject' => $idProject !== null ? (int)$idProject : null,
                'status' => $status,
                'assignedTo' => $assignedTo !== null ? (int)$assignedTo : null
            ]);
            return $stmt->rowCount(); // Retourne le nombre de lignes affectées
        } catch (PDOException $e) {
            // Journalisation de l'erreur
            error_log("Erreur lors de la mise à jour de la tâche : " . $e->getMessage());
            throw $e; // Relancer l'exception pour une gestion ultérieure
        }
    }

    public function deleteTask($taskId) {
        $sql = "DELETE FROM Task WHERE taskId = :taskId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['taskId' => $taskId]);
        return $stmt->rowCount(); // Retourne le nombre de lignes affectées
    }
}

