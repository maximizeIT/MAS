<?php

session_start();

// needed for prepared DB statements
include_once('./db/DBHandler_rw.php');

// Prevention CSRF attacks
include_once('./includes/nocsrf.php');

// Checks for input fields
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// check CSRF token	
	try
  {
    // Run CSRF check, on POST data, in exception mode, for 5 minutes, in one-time mode.
    if(NoCSRF::check( 'csrf_token_add_user', $_POST, true, 60 * 5, false ));
	}
  catch ( Exception $e )
  {
    // CSRF attack detected
    $errCSRF = $e->getMessage();
  }

  if (empty($_POST['user_username'])) {
  	$errUsername = "Enter a username.";
  }
  if (empty($_POST['user_password'])) {
  	$errUsername = "Enter a password.";
  }
  if (empty($_POST['user_role'])) {
  	$errUsername = "Select user role.";
  }
}

// Check all fields
if (empty($_POST['user_username']) || empty($_POST['user_password']) || empty($_POST['user_role']) || !empty($errCSRF) || !empty($errUsername) || !empty($errPassword) || !empty($errRole) || $_SERVER['REQUEST_METHOD'] == 'GET')
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
   	{
   		if (!empty($errCSRF)) {
           	$_SESSION['notify_error_add_user'] = $errCSRF;

			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('data_mgmt.php'));
            exit;
        }
        elseif (!empty($errUsername)) {
           	$_SESSION['notify_error_add_user'] = $errUsername;

			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('data_mgmt.php'));
            exit;
        }
        elseif (!empty($errPassword)) {
           	$_SESSION['notify_error_add_user'] = $errPassword;

			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('data_mgmt.php'));
            exit;
        }
        elseif (!empty($errRole)) {
           	$_SESSION['notify_error_add_user'] = $errRole;

			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('data_mgmt.php'));
            exit;
        }   	
   	}
   	else {
   		header('Status: 400'); // Status bad request
		header('Location: ../'.urlencode('data_mgmt.php'));
        exit;
   	}
}
else
{	
	// Create connection
	$db = new DbHandler();
	
	$db->start_transaction();

	$username = $_POST['user_username'];
	$password = $_POST['user_password'];
	$role = $_POST['user_role'];

	$result = $db->addUser($username, $password, $role);
		
    if ($result) 
	{
		$db->commit();

		$_SESSION['notify_success_add_user'] = "System user successfully added.";

		header('Status: 201'); // Status created
		header('Location: ../'.urlencode('data_mgmt.php'));
		exit;

    } 
	else 
	{
		$db->rollback();

		$_SESSION['notify_error_add_user'] = "Error while adding new system user. Contact admin.";
		
		header('Status: 400'); // Status bad request
		header('Location: ../'.urlencode('data_mgmt.php'));
		exit;
    }
}
?>