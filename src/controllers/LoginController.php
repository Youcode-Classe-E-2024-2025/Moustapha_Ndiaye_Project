<?php

require_once('../src/models/authentificationModel.php');
require_once('../config/config.php');

class UserController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Method to authenticate and log in the user
    public function loginUser($email, $passWord) {
        // Use loginUsers method to authenticate the user
        $user = User::loginUsers($this->pdo, $email, $passWord);

        if ($user) {
            // If the user is a "Visitor", update their role to "TeamMember"
            if ($user->getRole() == 'Visitor') {
                // Update role in the database
                $updateStmt = $this->pdo->prepare("UPDATE User SET role = ? WHERE email = ?");
                $updateStmt->execute(['TeamMember', $email]);

                // Update the user's role
                $user = new User($user->getFullName(), $user->getEmail(), '', 'TeamMember');
            }

            // Start the session and store user information
            session_start();
            $_SESSION['user_id'] = $user->getUserId();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_role'] = $user->getRole(); // Store role if necessary
            $_SESSION['success_message'] = "Login successful!"; // Success message

            // Redirect based on the user's role
            if ($user->getRole() == 'ProjectManager') {
                // Redirect to ProjectManager page
                header("Location: homeManager");
            } else {
                // Redirect to homeUser page for other roles
                header("Location: homeUser");
            }
            exit();
        } else {
            // Email or password is incorrect
            $_SESSION['error_message'] = "Invalid email or password.";
        }

        header("Location: loginView"); // Redirect to login page on error
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



// Start session to handle flash messages
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
