<?php

require_once('../src/models/authentificationModel.php');
require_once('../config/config.php');

class UserController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour vérifier les informations d'identification et connecter l'utilisateur
    public function loginUser($email, $passWord) {
        // Utilisation de la méthode loginUsers pour vérifier l'utilisateur
        $user = User::loginUsers($this->pdo, $email, $passWord);

        if ($user) {
            // Si l'utilisateur est un "Visitor", on le met à jour en "TeamMember"
            if ($user->getRole() == 'Visitor') {
                // Mise à jour du rôle dans la base de données
                $updateStmt = $this->pdo->prepare("UPDATE User SET role = ? WHERE email = ?");
                $updateStmt->execute(['TeamMember', $email]);

                // Mettre à jour le rôle de l'utilisateur
                $user = new User($user->getFullName(), $user->getEmail(), '', 'TeamMember');
            }

            // Démarrer la session et stocker les informations de l'utilisateur
            session_start();
            $_SESSION['user_id'] = $user->getUserId();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_role'] = $user->getRole(); // Ajouter rôle si nécessaire
            $_SESSION['success_message'] = "Login successful!"; // Message de succès

            // Rediriger vers la page appropriée en fonction du rôle de l'utilisateur
            if ($user->getRole() == 'ProjectManager') {
                // Rediriger vers la page ProjectManager
                header("Location: homeManager");
            } else {
                // Rediriger vers la page homeUser pour les autres rôles
                header("Location: homeUser");
            }
            exit();
        } else {
            // L'email ou le mot de passe est incorrect
            $_SESSION['error_message'] = "Invalid email or password.";
        }

        header("Location: loginView"); // Rediriger vers la page de login en cas d'erreur
        exit();
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: loginView");
        exit();
    }
}

class LoginValidator {
    private $email;
    private $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function validate() {
        $errors = [];

        if (empty($this->email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($this->password)) {
            $errors[] = "Password is required.";
        }

        return $errors;
    }
}

// Démarrer la session pour gérer les messages flash
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $passWord = $_POST['passWord'] ?? '';

    $validator = new LoginValidator($email, $passWord);
    $validationErrors = $validator->validate();

    $errors = array_merge($errors, $validationErrors);

    if (empty($errors)) {
        try {
            $pdo = new PDO(
                'mysql:host=localhost;dbname=' . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $controller = new UserController($pdo);
            $controller->loginUser($email, $passWord);
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
            header("Location: loginView");
            exit();
        }
    } else {
        $_SESSION['error_message'] = implode('<br>', $errors);
        header("Location: loginView");
        exit();
    }
}
