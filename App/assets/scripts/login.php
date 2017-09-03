<?php
// Error Reporting komplett abschalten
error_reporting(0);

session_destroy();
session_start();

// needed for prepared DB statements
include_once 'db/DBHandler.php';

// needed for CSRF token
include_once '../includes/nocsrf.php';

// Checks for input fields
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // check CSRF token
    try
    {
        // Run CSRF check, on POST data, in exception mode, for 1 minutes, in one-time mode.
        if (NoCSRF::check('csrf_token_user_login', $_POST, true, 60 * 1, false));
    } catch (Exception $e) {
        // CSRF attack detected
        $errCSRF = $e->getMessage();
    }

    if (empty($_POST['username'])) {
        $errUsername = "Enter your username.";
    }
    if (empty($_POST['password'])) {
        $errPassword = "Enter your password.";
    }

    // check for allowed username & password

}

// Check all fields again & for errors found
if (empty($_POST['username']) || empty($_POST['password']) || $_SERVER['REQUEST_METHOD'] == 'GET' || !empty($errCSRF)) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($errCSRF)) {
            $_SESSION['notify_error_user'] = $errCSRF;

            header('Status: 400'); // Status bad request
            header('Location: ../../' . urlencode('index.php'));
            exit;
        } elseif (!empty($errUsername)) {
            $_SESSION['notify_error_user'] = $errUsername;

            header('Status: 400'); // Status bad request
            header('Location: ../../' . urlencode('index.php'));
            exit;
        } elseif (!empty($errPassword)) {
            $_SESSION['notify_error_user'] = $errPassword;

            header('Status: 400'); // Status bad request
            header('Location: ../../' . urlencode('index.php'));
            exit;
        } else {
            $_SESSION['notify_error_user'] = "What are you trying?";

            header('Status: 400'); // Status bad request
            header('Location: ../../' . urlencode('index.php'));
            exit;
        }
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $_SESSION['notify_error_user'] = "Nice try...";

        header('Status: 400'); // Status bad request
        header('Location: ../../' . urlencode('index.php'));
        exit;
    }
} else // log in
{
    // db
    $db = new DbHandler();

    $username = $_POST['username'];
    $password = $_POST['password'];

    $db->start_transaction();

    // Get the user-agent string of the user.
    $user_browser = $_SERVER['HTTP_USER_AGENT'];

    $login = $db->userLogin($username, $password, $user_browser);

    // true if password is correct
    if ($login) {
        $db->commit();

        unset($_SESSION["notify_error_user"]);
        unset($_SESSION["notify_info_user"]);
        unset($_SESSION["user_username"]);
        unset($_SESSION["user_role"]);
        unset($_SESSION["user_login_string"]);

        // XSS protection as we might print this value
        // $username = preg_replace("/[^0-9]+/", "", $username);

        $role = $db->getUserRole($username);

        // assign session vars
        $_SESSION['user_username']     = $username;
        $_SESSION['user_role']         = $role;
        $_SESSION['user_login_string'] = $login;

        if ($role === "Employee") {
            // redirect to search interface
            header('Status: 200'); // Status ok
            header('Location: ../../search/' . urlencode('index.php'));
            exit;
        } elseif ($role === "Admin") {
            // redirect to admin area
            header('Status: 200'); // Status ok
            header('Location: ../../admin/' . urlencode('index.php'));
        } else {
            // Login failed
            $_SESSION['notify_error_user'] = "Don't know what's happening...";

            // redirect to login page
            header('Status: 401'); // Status unauthorized
            header('Location: ../../' . urlencode('index.php'));
            exit;
        }

    } else {
        $db->rollback();

        // Login failed
        $_SESSION['notify_error_user'] = "Incorrect username or password.";

        // redirect to login page
        header('Status: 401'); // Status unauthorized
        header('Location: ../../' . urlencode('index.php'));
        exit;
    }
}
