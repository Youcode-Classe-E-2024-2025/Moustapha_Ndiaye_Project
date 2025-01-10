<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // import require file
    require_once('../config/config.php') ;
    require_once('../config/loadDatabase.php');
    require_once('../src/controllers/authentificationController.php');

    // create database
    $db = new database() ;
    $pdo = $db->connexion();

    // load script database 
    $loader = new LoadDatabase($pdo, '../database/schemaDatabase.sql');
    $loader->fetchData();

    // token
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/input.css">
    <link rel="stylesheet" href="assets/css/output.css">
</head>
<body class="bg-gradient-to-br from-blue-100 to-white min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
     <!-- handle error -->
     

        <?php 
            if (isset($_SESSION['success_message'])) {
                echo '<div class="bg-green-100 p-4 rounded-lg text-green-700" role="alert" aria-live="polite">' 
                    . htmlspecialchars($_SESSION['success_message']) . 
                    '</div>';
                unset($_SESSION['success_message']);  
            }
            
            if (isset($_SESSION['error_message'])) {
                echo '<div class="bg-red-100 p-4 rounded-lg text-red-700" role="alert" aria-live="polite">' 
                    . htmlspecialchars($_SESSION['error_message']) . 
                    '</div>';
                unset($_SESSION['error_message']);  
            }
            ?>

        
    <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-800">Create an Account</h2>
            <p class="mt-2 text-gray-600">Register to get started</p>
        </div>

        <!-- Social Login -->
        <div class="flex justify-center space-x-4" id="socialLogin">
            <!--  GitHub -->
            <button class="p-3 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                </svg>
            </button>

            <!--  Google -->
            <button
                class="flex items-center justify-center p-2 rounded-full bg-white border border-gray-300 hover:bg-gray-50 transition duration-300"
                aria-label="Sign in with Google">
                <svg class="w-6 h-6" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    <path fill="none" d="M0 0h48v48H0z"/>
                </svg>
            </button>
        </div>

       
        <div class="my-4 flex items-center before:mt-0.5 before:flex-1 before:border-t before:border-neutral-300 after:mt-0.5 after:flex-1 after:border-t after:border-neutral-300">
            <p class="mx-4 mb-0 text-center font-semibold">Or</p>
        </div>
 
        <form action="registerView" method="POST">
            <div class="relative mb-6">
                <input
                    type="text"
                    id="name"
                    name="fullName"
                    pattern="[A-Za-z\s]{2,}"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-white px-3 py-[0.32rem] leading-[2.15]
                    transition-all duration-200 ease-linear placeholder-transparent focus:placeholder-transparent"
                    placeholder="Name" />
                <label
                    for="name"
                    class="absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15]
                    text-neutral-500 transition-all duration-200 ease-out dark:peer-focus:bg-white">
                    Full Name
                </label>
            </div>

            <div class="relative mb-6">
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-white px-3 py-[0.32rem] leading-[2.15]
                    transition-all duration-200 ease-linear placeholder-transparent focus:placeholder-transparent"
                    placeholder="Email" />
                <label
                    for="email"
                    class="absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15]
                    text-neutral-500 transition-all duration-200 ease-out dark:peer-focus:bg-white">
                    Email
                </label>
            </div>

            <div class="relative mb-6">
                <input
                    name="passWord"
                    type="password"
                    id="password"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-white px-3 py-[0.32rem] leading-[2.15]
                    transition-all duration-200 ease-linear placeholder-transparent focus:placeholder-transparent"
                    placeholder="Password" />
                <label
                    for="password"
                    class="absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15]
                    text-neutral-500 transition-all duration-200 ease-out dark:peer-focus:bg-white">
                    Password
                </label>
            </div>

            <div class="relative mb-6">
                <input
                    name="confirmPassword"
                    type="password"
                    id="confirmPassword"
                    class="peer block min-h-[auto] w-full rounded border-0 bg-white px-3 py-[0.32rem] leading-[2.15]
                    transition-all duration-200 ease-linear placeholder-transparent focus:placeholder-transparent"
                    placeholder="Confirm Password" />
                <label
                    for="confirmPassword"
                    class="absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[2.15]
                    text-neutral-500 transition-all duration-200 ease-out dark:peer-focus:bg-white">
                    Confirm Password
                </label>
            </div>

            <div class="text-center lg:text-left">
                <button
                    type="submit"
                    class="inline-block w-full rounded bg-blue-600 px-7 pb-2 pt-3 text-sm font-medium uppercase leading-normal text-white shadow-primary-3 transition duration-150 ease-in-out hover:bg-blue-700 focus:bg-blue-700 focus:shadow-primary-2 focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-primary-2"
                    data-twe-ripple-init
                    data-twe-ripple-color="light">
                    Register
                </button>
            </div>
        </form>

        <p class="text-center text-sm">
            Already have an account?
            <a href="loginView" class="font-medium text-blue-600 hover:underline">Sign in</a>
        </p>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>