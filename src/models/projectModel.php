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
}