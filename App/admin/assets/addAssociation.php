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
        if(NoCSRF::check( 'csrf_token_add_association', $_POST, true, 60 * 5, false ));
	}
    catch ( Exception $e )
    {
        // CSRF attack detected
        $errCSRF = $e->getMessage();
    }

    if (empty($_POST['association_name'])) {
    	$errName = "Enter a name.";
    }
}

//Check all fields
if (empty($_POST['association_name']) || !empty($errCSRF) || !empty($errName) || $_SERVER['REQUEST_METHOD'] == 'GET')
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
   	{
   		if (!empty($errCSRF)) {
           	$_SESSION['notify_error_add_association'] = $errCSRF;

			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('data_mgmt.php'));
            exit;
        }
        elseif (!empty($errName)) {
           	$_SESSION['notify_error_add_association'] = $errName;

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

	$name = $_POST['association_name'];
	$website = $_POST['association_website'];

	$result = $db->addAssociation($name, $website);
		
    if ($result) 
	{
		$db->commit();

		$_SESSION['notify_success_add_association'] = "Association successfully added.";
			
		header('Status: 201'); // Status created
		header('Location: ../'.urlencode('data_mgmt.php'));
		exit;

    } 
	else 
	{
		$db->rollback();

		$_SESSION['notify_error_add_association'] = "Error while adding new association. Contact admin.";

		
		header('Status: 400'); // Status bad request
		header('Location: ../'.urlencode('data_mgmt.php'));
		exit;
    }
}
 ?>