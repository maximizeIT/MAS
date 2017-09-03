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
		if(NoCSRF::check( 'csrf_token_delete_membership_single', $_POST, true, 60 * 5, false ));
	}
	catch ( Exception $e )
	{
		// CSRF attack detected
		$errCSRF = $e->getMessage();
	}

	if (empty($_POST['membershipDeleteSingle'])) {
		$errSelection = "Select a membership to delete.";
	}
}

//Check all fields
if (empty($_POST['membershipDeleteSingle']) || !empty($errCSRF) || !empty($errSelection) || $_SERVER['REQUEST_METHOD'] == 'GET')
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
   	{
   		if (!empty($errCSRF)) {
           	$_SESSION['notify_error_delete_membership'] = $errCSRF;

			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('memberships.php'));
            exit;
        }
        elseif (!empty($errSelection)) {
           	$_SESSION['notify_error_delete_membership'] = $errSelection;

			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('memberships.php'));
            exit;
        }    	
   	}
   	else {
   		header('Status: 400'); // Status bad request
		header('Location: ../'.urlencode('memberships.php'));
        exit;
   	}
}
else
{	
	// Create connection
	$db = new DbHandler();
	
	$db->start_transaction();

	$membershipId = $_POST['membershipDeleteSingle'];
	
	$result = $db->deleteMembershipSingle($membershipId);
		
	if($result)
	{
		$db->commit();

		$_SESSION['notify_success_delete_membership'] = "Membership successfully deleted.";
		
		header('Status: 200'); // status ok
		header('Location: ../'.urlencode('memberships.php'));
		exit;
	}
	else
	{
		$db->rollback();

		$_SESSION['notify_error_delete_membership'] = "Error while deleting Membership. Contact admin.";
		
		header('Status: 400'); // status bad request
		header('Location: ../'.urlencode('memberships.php'));
		exit;
	}
}
 ?>