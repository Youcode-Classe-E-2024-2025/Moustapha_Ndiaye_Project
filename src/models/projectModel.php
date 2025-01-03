<?php


class Project {
    private $idProject ;
    private $projectTitle ;
    private $projectDescrip ;
    private $category ;
    private $startAt ;
    private $endAt ;
    private $isPublic ;

    public function __construct($idProject ,$projectTitle ,$projectDescrip ,$category ,$startAt ,$endAt, $isPublic = 1 ){
        $this->idProject = $idProject ;
        $this->projectTitle = $projectTitle ;
        $this->projectDescrip = $projectDescrip ;
        $this->startAt = $startAt ;
        $this->endAt = $endAt ;
        $this->isPublic = $isPublic ;
    }

    // getters
    public function getidProject() {return $this->idProject;}
    public function getprojectTitle() {return $this->projectTitle;}
    public function getprojectDescrip() {return $this->projectDescrip;}
    public function getcategory() {return $this->category;}
    public function getstartAt() {return $this->startAt;}
    public function getendAt() {return $this->endAt;}
    public function getisPublic() {return $this->isPublic;}

    public function createProject($pdo){
        $statement = $pdo->prepare("
            INSERT INTO Project (projectTitle, projectDescrip, category, startAt, endAt, createdBy, isPublic) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $statement->execute([
            $this->idProject = $idProject ,
            $this->projectTitle = $projectTitle ,
            $this->projectDescrip = $projectDescrip ,
            $this->startAt = $startAt ,
            $this->endAt = $endAt ,
            $this->isPublic = $isPublic 
        ]) ;
    }

    public function deleteProject($pdo, $idProject){
        $statement = $pdo->prepare("
            DELETE FROM Project WHERE idProject = ?
        ");

        $statement->execute([$idProject]);
    }

    public function addUserToProject($pdo, $userId){
        $statement = $pdo->prepare("INSERT INTO UserProject (userId, projectId) VALUES (?, ?)");
        $statement->execute([$userId, $this->idProject]);
    }
}



class Task {
    private $taskId;
    private $taskTitle;
    private $taskDescrip;
    private $startAt;
    private $endAt;
    private $projectId;
    private $status;

    public function __construct($taskTitle, $taskDescrip, $startAt, $endAt, $projectId, $status = 'Todo') {
        $this->taskTitle = $taskTitle;
        $this->taskDescrip = $taskDescrip;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->projectId = $projectId;
        $this->status = $status;
    }

    // Getters
    public function getTaskId() { return $this->taskId; }
    public function getTaskTitle() { return $this->taskTitle; }
    public function getTaskDescrip() { return $this->taskDescrip; }
    public function getStartAt() { return $this->startAt; }
    public function getEndAt() { return $this->endAt; }
    public function getProjectId() { return $this->projectId; }
    public function getStatus() { return $this->status; }

    public function createTask($pdo) {
        $stmt = $pdo->prepare("INSERT INTO Task (taskTitle, taskDescrip, startAt, endAt, projectId, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$this->taskTitle, $this->taskDescrip, $this->startAt, $this->endAt, $this->projectId, $this->status]);
        $this->taskId = $pdo->lastInsertId();
    }

    public function updateTask($pdo) {
        $stmt = $pdo->prepare("UPDATE Task SET taskTitle = ?, taskDescrip = ?, startAt = ?, endAt = ?, status = ? WHERE taskId = ?");
        $stmt->execute([$this->taskTitle, $this->taskDescrip, $this->startAt, $this->endAt, $this->status, $this->taskId]);
    }

    public static function deleteTask($pdo, $taskId) {
        $stmt = $pdo->prepare("DELETE FROM Task WHERE taskId = ?");
        $stmt->execute([$taskId]);
    }

    public function assignUserToTask($pdo, $userId) {
        $stmt = $pdo->prepare("INSERT INTO UserTask (userId, taskId) VALUES (?, ?)");
        $stmt->execute([$userId, $this->taskId]);
    }

    public function addTagToTask($pdo, $tagId) {
        $stmt = $pdo->prepare("INSERT INTO TaskTag (taskId, tagId) VALUES (?, ?)");
        $stmt->execute([$this->taskId, $tagId]);
    }
}


class Tag {
    private $idTag;
    private $nameTag;

    public function __construct($nameTag) {
        $this->nameTag = $nameTag;
    }

    public function getIdTag() { return $this->idTag; }
    public function getNameTag() { return $this->nameTag; }

    public function createTag($pdo) {
        $stmt = $pdo->prepare("INSERT INTO Tag (nameTag) VALUES (?)");
        $stmt->execute([$this->nameTag]);
        $this->idTag = $pdo->lastInsertId();
    }

    public function updateTag($pdo) {
        $stmt = $pdo->prepare("UPDATE Tag SET nameTag = ? WHERE idTag = ?");
        $stmt->execute([$this->nameTag, $this->idTag]);
    }

    public static function deleteTag($pdo, $idTag) {
        $stmt = $pdo->prepare("DELETE FROM Tag WHERE idTag = ?");
        $stmt->execute([$idTag]);
    }
}