<?php

class User {
    private $userId;
    private $fullName;
    private $email;
    private $passWord;
    private $role;

    public function __construct($fullName = '', $email = '', $passWord = '', $role = 'Visitor'){
        $this->fullName = $fullName;
        $this->email = $email;
        $this->passWord = $passWord;
        $this->role = $role;
    }

    // Getters
    public function getUserId() { return $this->userId; }
    public function getFullName() { return $this->fullName; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }

    // Register a new user
    public function registerUser($pdo){
        try {
            // Hachage du mot de passe
            $hashed_passWord = password_hash($this->passWord, PASSWORD_BCRYPT);
        
            // Préparation de la requête SQL
            $statement = $pdo->prepare("INSERT INTO User (fullName, email, passWord, role) VALUES (?, ?, ?, ?)");
            
            // Exécution de la requête avec les bons paramètres
            $statement->execute([
                $this->fullName,    // fullName
                $this->email,       // email
                $hashed_passWord,   // mot de passe haché
                $this->role         // rôle
            ]);
            
            // Récupération de l'ID de l'utilisateur nouvellement inséré
            $this->userId = $pdo->lastInsertId();
        } catch (PDOException $e) {
            // Gérer l'exception en cas d'erreur
            echo "Erreur d'inscription : " . $e->getMessage();
        }
    }
    
    // Login user
    public static function loginUsers($pdo, $email, $passWord){
        try {
            $stmnt = $pdo->prepare("SELECT * FROM User WHERE email = ?");
            $stmnt->execute([$email]);
            $user = $stmnt->fetch(PDO::FETCH_ASSOC);

            // Vérification du mot de passe
            if ($user && password_verify($passWord, $user['passWord'])){
                return new User($user['fullName'], $user['email'], '', $user['role']);
            }
        } catch (PDOException $e) {
            // Gérer l'exception en cas d'erreur
            echo "Erreur de connexion : " . $e->getMessage();
        }
        return null;
    }
}
