<?php

$request_URI = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function safeRequire($path) {
    if (file_exists($path)) {
        require_once $path;
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Page not found";
        exit();
    }
}

switch ($request_URI) {
    case '/':
    case '/landingPageView':
        safeRequire('../src/views/landingPageView.php');
        break;

    case '/loginView':
        safeRequire('../src/views/loginView.php');
        break;

    case '/registerView':
        safeRequire('../src/views/registerView.php');
        break;
    case '/homeUser':
        safeRequire('../src/views/homeUser.php');
        break;
    
    case '/homeManager':
        safeRequire('../src/views/homeManager.php');
        break;
    case '/loginController':
        safeRequire('../src/controllers/LoginController.php');
        break;
        

    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page not found";
        break;
}