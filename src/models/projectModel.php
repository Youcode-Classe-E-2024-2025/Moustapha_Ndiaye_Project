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

    
}