<?php

class Visitor extends User {
    
    public function seePublicProject($pdo){
        $statement = $pdo->query("SELECT * FROM Project WHERE isPublic = 1");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

class TeamMember extends User {
    
    public function seeAssociatedProject($pdo, $userId){
        $statement = $pdo->prepare("SELECT p.* FROM Project p JOIN UserProject up ON p.idProject = up.projectId WHERE up.userId = ?");
        $statement->execute([$userId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function updateStatusTask($pdo, $taskId, $status){
        $statement = $pdo->prepare("UPDATE Task SET status = ? WHERE idTask = ?");
        $statement->execute([$status, $taskId]);
    }
}

class ProjectManager extends User {
    
    public function assignTaskToMember($pdo, $userId, $taskId){
        // Check if the task and user exist
        $taskExists = $pdo->prepare("SELECT idTask FROM Task WHERE idTask = ?");
        $taskExists->execute([$taskId]);
        $userExists = $pdo->prepare("SELECT id_users FROM Users WHERE id_users = ?");
        $userExists->execute([$userId]);

        if ($taskExists->fetch() && $userExists->fetch()) {
            $statement = $pdo->prepare("INSERT INTO UserTask (userId, taskId) VALUES (?, ?)");
            $statement->execute([$userId, $taskId]);
        } else {
            throw new Exception("The task or user does not exist.");
        }
    }

    
    public function checkProgressProject($pdo, $idProject){
        $statement = $pdo->prepare("SELECT COUNT(*) as total, SUM(status = 'Done') as completed FROM Task WHERE projectId = ?");
        $statement->execute([$idProject]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}