<?php
// needed for prepared DB statements
include_once('db/DBHandler_rw.php');

// Prevention CSRF attacks
include_once('includes/nocsrf.php');

// Checks for input fields
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// check CSRF token	
	try
    {
        // Run CSRF check, on POST data, in exception mode, for 5 minutes, in one-time mode.
        if(NoCSRF::check( 'csrf_token_update_member', $_POST, true, 60 * 5, false ));
	}
    catch ( Exception $e )
    {
        // CSRF attack detected
        $errCSRF = $e->getMessage();
    }
    if (empty($_POST['member_id'])) {
    	$err = "No member id provided.";
    }    

    if( ($_POST['fname'] != $_POST['fname_org']) || ($_POST['lname'] != $_POST['lname_org']) || ($_POST['email'] != $_POST['email_org']) || ($_POST['student_nr'] != $_POST['student_nr_org']) )
    {
    	if (empty($_POST['fname']) || empty($_POST['lname'])) {
    		$err = "Please provide first and last name.";
    	}
      if (empty($_POST['email'])) {
        $err = "Please provide an email address.";
      }
    }
    else
    {
    	$_SESSION['notify_success_update_member'] = "Cheers mate, Nothing to update.";
		  header('Status: 201'); // Status created
		  header('Location: ../'.urlencode('members_edit.php'));
      exit;
    }
}

// Check all fields
if (empty($_POST['member_id']) || empty($_POST['fname']) || empty($_POST['lname']) || !empty($errCSRF) || !empty($err) || $_SERVER['REQUEST_METHOD'] == 'GET')
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
   	{
   		if (!empty($errCSRF)) {

           	$_SESSION['notify_error_update_member'] = $errCSRF;
			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('members_edit.php'));
            exit;
        }
        elseif (!empty($err)) {

           	$_SESSION['notify_error_update_member'] = $err;
			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('members_edit.php'));
            exit;
        }    	
   	}
   	else {
   		$_SESSION['notify_error_update_member'] = "Mate, what are you trying?";
   		header('Status: 400'); // Status bad request
		header('Location: ../'.urlencode('members_edit.php'));
        exit;
   	}
}
else
{	
	// Create connection
	$db = new DbHandler();
	
	$db->start_transaction();

	$memberId = $_POST['member_id'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$studentNr = $_POST['student_nr'];

	$result = $db->updateMemberSingle($memberId, $email, $fname, $lname, $studentNr);
		
    if ($result) 
	{
		$db->commit();
			
		$_SESSION['notify_success_update_member'] = "Member updated.";
		header('Status: 201'); // Status created
		header('Location: ../'.urlencode('members_edit.php'));
		exit;

    } 
	else 
	{
		$db->rollback();
		
		$_SESSION['notify_error_update_member'] = "Something went wrong on the update... Contact admin.";
		header('Status: 400'); // Status bad request
		header('Location: ../'.urlencode('members_edit.php'));
		exit;
    }
}
 ?>