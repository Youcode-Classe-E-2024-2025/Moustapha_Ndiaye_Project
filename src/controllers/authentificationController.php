<?php

class UserController {
    private $pdo ;

    public function __construct($pdo){
        $this->pdo = $pdo ;
    }

    public function registerUser($fullName, $email, $passWord, $role = 'Visitor'){
        $allowRoles = ['Visitor', 'TeamMember', 'ProjectManager'];

        if (!in_array($role, $allowRoles)){
            throw new Exeption("Role does not exist") ;
        }

        $user = new User($fullName, $email, $passWord, $role);
        $user = $pdo->registerUser($pdo);
    }


    public function loginUser($email, $passWord){
        return User::loginUser($this->pdo, $email, $passWord) ;
    }

    public function logout(){
        session_start();
        session_destroy();
        header("Location : login.php") ;
        exit();
    }
}