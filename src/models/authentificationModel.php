<?php

class UserModel {
    private $userId;
    private $fullName ;
    private $email ;
    private $passWord ;
    private $role ;

    public function __construct($fullName = '', $email = '', $passWord = '', $role = 'Visitor'){
        $this->userId = $userId;
        $this->fullName = $fullName;
        $this->email = $passWord;
        $this->passWord = $passWord;
        $this->role = $role;
    }
    // getters
    public function getUserId() {return $this->userId ;}
    public function getFullName() {return $this->fullName ;}
    public function getEmail() {return $this->email ;}
    public function getRole() {return $this->role ;}

    // register
    public function registerUser($pdo){
        // hash passWord
        $hashed_passWord = password_hash($this->passWord, PASSWORD_BCRYPT) ;

        // PREPARTE SQL REQUEST
        $statement = $pdo->prepare("INSERT INTO User ($fullName, $email, $passWord, $role) VALUES (?,?,?,?)");
        $statement->exec([
            $this->userId,
            $this->fullName,
            $this->email,
            $this->passWord,
            $this->role 
        ]);
        $this->userId = $pdo->lastInsertId();
    }
    // login 
    public static function loginUsers($pdo, $email, $passWord){
        $stmnt = $pdo->prepare("SELECT FROM User WHERE email = ?");
        $stmnt = exec([$email]) ;
        $user = $stmnt->fetch(PDO::FETCH_ASSOC); 

    // check data 
    if ($user && password_verify($passWord, $user['passWord'])){
        return new User($user['fullName'], $user['email'], '', $user['role']);
    }
    return null ;
    }
}



