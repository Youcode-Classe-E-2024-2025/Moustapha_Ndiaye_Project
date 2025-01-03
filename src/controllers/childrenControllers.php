<?php

class VisitorController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
    public function seePublicProjects() {
        $visitor = new Visitor();
        return $visitor->seePublicProject($this->pdo);
    }
}

class TeamMemberController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
    public function seeAssociatedProjects($userId) {
        $teamMember = new TeamMember();
        return $teamMember->seeAssociatedProject($this->pdo, $userId);
    }

    
    public function updateStatusTask($taskId, $status) {
        $teamMember = new TeamMember();
        return $teamMember->updateStatusTask($this->pdo, $taskId, $status);
    }
}

class ProjectManagerController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
    public function assignTaskToMembers($userId, $taskId) {
        $projectManager = new ProjectManager();
        return $projectManager->assignTaskToMember($this->pdo, $userId, $taskId);
    }

    
    public function checkProgressProjects($idProject) {
        $projectManager = new ProjectManager();
        return $projectManager->checkProgressProject($this->pdo, $idProject);
    }
}