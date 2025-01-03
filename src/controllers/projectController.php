<?php


class ProjectController {
   private $pdo;


   public function __construct($pdo){
    $this->pdo = $pdo ;
   }

   public function addProjects($projectTitle, $projectDescrip, $category, $startAt, $endAt, $createdBy, $isPublic = 1){
    $project = new Project(null,$projectTitle, $projectDescrip, $category, $startAt, $endAt, $createdBy, $isPublic);

    $project->addProject($this->pdo);
    }

    public function deleteProjects($idProject){
        $project = new Project($idProject,null, null, null, null, null, null, null);
        $project->deleteProject($this->pdo, $idProject);
    }


    public function addUserToProjects($idProject,$userId){
        $project = new Project($idProject,null, null, null, null, null, null, null);
        $project->addUserToProject($this->pdo, $idProject,$userId);
    }
}



class TaskController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createTask($taskTitle, $taskDescrip, $startAt, $endAt, $projectId, $status = 'Todo') {
        $task = new Task($taskTitle, $taskDescrip, $startAt, $endAt, $projectId, $status);
        $task->createTask($this->pdo);
    }

    public function updateTasks($taskId, $taskTitle, $taskDescrip, $startAt, $endAt, $status) {
        $task = new Task($taskTitle, $taskDescrip, $startAt, $endAt, null, $status);
        $task->updateTask($this->pdo);
    }

    public function deleteTasks($taskId) {
        Task::deleteTask($this->pdo, $taskId);
    }

    public function assignUserToTasks($taskId, $userId) {
        $task = new Task(null, null, null, null, null, null);
        $task->assignUserToTask($this->pdo, $userId);
    }

    public function addTagToTasks($taskId, $tagId) {
        $task = new Task(null, null, null, null, null, null);
        $task->addTagToTask($this->pdo, $tagId);
    }
}


class TagController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createTag($nameTag) {
        $tag = new Tag($nameTag);
        $tag->createTag($this->pdo);
    }

    public function updateTag($idTag, $nameTag) {
        $tag = new Tag($nameTag);
        $tag->updateTag($this->pdo);
    }

    public function deleteTag($idTag) {
        Tag::deleteTag($this->pdo, $idTag);
    }

}