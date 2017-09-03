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
    if(NoCSRF::check( 'csrf_token_add_officeperiod', $_POST, true, 60 * 5, false ));
	}
  catch ( Exception $e )
  {
    // CSRF attack detected
    $errCSRF = $e->getMessage();
  }

  if (empty($_POST['period_date_starts'])) {
  	$errDateStart = "Enter a start date.";
  }
  if (empty($_POST['period_date_ends'])) {
  	$errDateEnd = "Enter an end date.";
  }
}

// Check all fields
if (empty($_POST['period_date_starts']) || empty($_POST['period_date_ends']) || !empty($errCSRF) || !empty($errDateStart) || !empty($errDateEnd) || $_SERVER['REQUEST_METHOD'] == 'GET')
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
   	{
   		if (!empty($errCSRF)) {
           	$_SESSION['notify_error_add_officeperiod'] = $errCSRF;

			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('data_mgmt.php'));
            exit;
        }
        elseif (!empty($errDateStart)) {
           	$_SESSION['notify_error_add_officeperiod'] = $errDateStart;

			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('data_mgmt.php'));
            exit;
        }
        elseif (!empty($errDateEnd)) {
           	$_SESSION['notify_error_add_officeperiod'] = $errDateEnd;

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

	$dateStart = $_POST['period_date_starts'];
	$dateEnd = $_POST['period_date_ends'];

  $dateStartRequired = date("Y-m-d", strtotime($dateStart));
  $dateEndRequired = date("Y-m-d", strtotime($dateEnd));

	$result = $db->addOfficePeriod($dateStartRequired, $dateEndRequired);
		
    if ($result) 
	{
		$db->commit();

		$_SESSION['notify_success_add_officeperiod'] = "Period of office successfully added.";

		header('Status: 201'); // Status created
		header('Location: ../'.urlencode('data_mgmt.php'));
		exit;

    } 
	else 
	{
		$db->rollback();

		$_SESSION['notify_error_add_officeperiod'] = "Error while adding new period of office. Contact admin.";
		
		header('Status: 400'); // Status bad request
		header('Location: ../'.urlencode('data_mgmt.php'));
		exit;
    }
}
?>