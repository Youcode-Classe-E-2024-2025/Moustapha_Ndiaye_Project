<?php
class UserRegistrationException extends Exception {}

class User {
    private $userId;
    private $fullName;
    private $email;
    private $passWord;
    private $role;

    public function __construct($fullName = '', $email = '', $passWord = '', $role = 'Visitor') {
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

    public function isEmailUnique($pdo) {
        $statement = $pdo->prepare("SELECT COUNT(*) FROM User WHERE email = ?");
        $statement->execute([$this->email]);
        return $statement->fetchColumn() === 0; 
    }

    public function registerUser($pdo) {
        try {
            if (!$this->isEmailUnique($pdo)) {
                throw new UserRegistrationException("Email is already registered.");
            }

            $hashed_passWord = password_hash($this->passWord, PASSWORD_BCRYPT);

            $statement = $pdo->prepare("INSERT INTO User (fullName, email, passWord, role) VALUES (?, ?, ?, ?)");

            $statement->execute([
                $this->fullName,    
                $this->email,       
                $hashed_passWord,   
                $this->role        
            ]);

            $this->userId = $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            throw new UserRegistrationException("Registration failed. Please try again later.");
        }
    }

    // Login user
    public static function loginUsers($pdo, $email, $passWord) {
        try {
            $stmnt = $pdo->prepare("SELECT * FROM User WHERE email = ?");
            $stmnt->execute([$email]);
            $user = $stmnt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($passWord, $user['passWord'])) {
                return new User($user['fullName'], $user['email'], '', $user['role']);
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            throw new UserRegistrationException("Login failed. Please try again later.");
        }
        return null;
    }
}