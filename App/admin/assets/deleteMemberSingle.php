<?php

session_start();

// needed for prepared DB statements
include_once('db/DBHandler_rw.php');

// Prevention CSRF attacks
include_once('./includes/nocsrf.php');

// Checks for input fields
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// check CSRF token	
	try
	{
		// Run CSRF check, on POST data, in exception mode, for 5 minutes, in one-time mode.
		if(NoCSRF::check( 'csrf_token_delete_member_single', $_POST, true, 60 * 5, false ));
	}
	catch ( Exception $e )
	{
		// CSRF attack detected
		$errCSRF = $e->getMessage();
	}

	if (empty($_POST['memberDeleteSingle'])) {
		$errSelection = "Select member to delete.";
	}
}

//Check all fields
if (empty($_POST['memberDeleteSingle']) || !empty($errCSRF) || !empty($errSelection) || $_SERVER['REQUEST_METHOD'] == 'GET')
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
   	{
   		if (!empty($errCSRF)) {
           	$_SESSION['notify_error_delete_member'] = $errCSRF;

			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('index.php'));
            exit;
        }
        elseif (!empty($errSelection)) {
           	$_SESSION['notify_error_delete_member'] = $errSelection;

			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('index.php'));
            exit;
        }    	
   	}
   	else {
   		header('Status: 400'); // Status bad request
		header('Location: ../'.urlencode('index.php'));
        exit;
   	}
}
else // delete multiple members
{	
	// Create connection
	$db = new DbHandler();
	
	$db->start_transaction();

	$memberId = $_POST['memberDeleteSingle'];
	
	$result = $db->deleteMemberSingle($memberId);
		
	if($result)
	{
		$db->commit();

		$_SESSION['notify_success_delete_member'] = "Member successfully deleted.";
		
		header('Status: 200'); // status ok
		header('Location: ../'.urlencode('index.php'));
		exit;
	}
	else
	{
		$db->rollback();

		$_SESSION['notify_error_delete_member'] = "Error while deleting member. Contact admin.";
		
		header('Status: 400'); // status bad request
		header('Location: ../'.urlencode('index.php'));
		exit;
	}
}
 ?>