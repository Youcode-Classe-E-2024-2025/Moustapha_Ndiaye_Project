<?php
require_once('../src/models/authentificationModel.php');
require_once('../config/config.php');

class UserController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registerUser($fullName, $email, $passWord, $role = 'Visitor') {
        $allowedRoles = ['Visitor', 'TeamMember', 'ProjectManager'];

        if (!in_array($role, $allowedRoles)) {
            throw new Exception("Invalid role specified.");
        }

        $user = new User($fullName, $email, $passWord, $role);
        $user->registerUser($this->pdo);
    }

    public function loginUser($email, $passWord) {
        return User::loginUsers($this->pdo, $email, $passWord);
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

class RegisterValidator {
    const MIN_PASSWORD_LENGTH = 8;

    private $fullName;
    private $email;
    private $password;
    private $confirmPassword;
    private $role;

    public function __construct($fullName, $email, $password, $confirmPassword, $role = 'Visitor') {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
        $this->role = $role;
    }

    public function validate() {
        $errors = [];

        if (empty($this->fullName)) {
            $errors[] = "Full name is required.";
        } elseif (strlen($this->fullName) > 100) {
            $errors[] = "Full name must not exceed 100 characters.";
        }

        if (empty($this->email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        } elseif (strlen($this->email) > 255) {
            $errors[] = "Email must not exceed 255 characters.";
        }

        if (empty($this->password)) {
            $errors[] = "Password is required.";
        } elseif (strlen($this->password) < self::MIN_PASSWORD_LENGTH) {
            $errors[] = "Password must be at least " . self::MIN_PASSWORD_LENGTH . " characters long.";
        }

        if ($this->password !== $this->confirmPassword) {
            $errors[] = "Passwords do not match.";
        }

        return $errors;
    }
}

class ErrorHandler {
    public static function handle($exception) {
        // Log the error message
        error_log($exception->getMessage());
        
        // Store a user-friendly error message in session
        $_SESSION['error_message'] = "An error occurred. Please try again later.";
    }
}

session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $passWord = $_POST['passWord'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    $validator = new RegisterValidator($fullName, $email, $passWord, $confirmPassword);
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
            $controller->registerUser($fullName, $email, $passWord);

            $_SESSION['success_message'] = "User registered successfully!";
            header("Location: registerView");
            exit();
        } catch (Exception $e) {
            ErrorHandler::handle($e);
            header("Location: registerView");
            exit();
        }
    } else {
        $_SESSION['error_message'] = implode("\n", $errors);
        header("Location: registerView");
        exit();
    }
}
