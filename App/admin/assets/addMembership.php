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
        if(NoCSRF::check( 'csrf_token_add_membership', $_POST, true, 60 * 5, false ));
	}
    catch ( Exception $e )
    {
        // CSRF attack detected
        $errCSRF = $e->getMessage();
    }

    if (empty($_POST['selectMember'])) {
    	$err = "Select a member.";
    }

    if (isset($_POST['checkboxCustomPeriod']) && $_POST['checkboxCustomPeriod'] == 'on') {
    	if(empty($_POST['selectCustomReason']))
    	{
    		$err = "Enter reason for custom period of office.";
    	}
    	elseif (empty($_POST['selectCustomStart']))
    	{
    		$err = "Select custom period start date.";
    	}
    	elseif (empty($_POST['selectCustomEnd']))
    	{
    		$err = "Select custom period end date.";
    	}
    }
    else {
    	if (empty($_POST['selectPeriod'])) {
    		$err = "Select an official period of office.";
    	}
    }

    if (empty($_POST['selectCommittee']) && empty($_POST['selectAssociation'])) {
    	if ($_POST['selectCommittee'] === '1')
    	{
    		$err = "Select an association if committee is StuRa.";
    	}
    	else
    	{
    		$err = "Select a committee or association.";
    	}
    }
}

//Check all fields
if (empty($_POST['selectMember']) || !empty($errCSRF) || !empty($err) || $_SERVER['REQUEST_METHOD'] == 'GET')
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
   	{
   		if (!empty($errCSRF)) {

           	$_SESSION['notify_error_add_membership'] = $errCSRF;
			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('memberships.php'));
            exit;
        }
        elseif (!empty($err)) {

           	$_SESSION['notify_error_add_membership'] = $err;
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

	$memberId = $_POST['selectMember'];

	$is_checked = isset($_POST['checkboxCustomPeriod']) && $_POST['checkboxCustomPeriod'] == 'on';

	$committeId = $_POST['selectCommittee'];
	$associationId = $_POST['selectAssociation'];

	if ($is_checked == 'on') 
	{
		$customReason = $_POST['selectCustomReason'];

		$customDateStart = $_POST['selectCustomStart'];
		$customDateStartRequired = date("Y-m-d", strtotime($customDateStart));

		$customDateEnd = $_POST['selectCustomEnd'];
		$customDateEndRequired = date("Y-m-d", strtotime($customDateEnd));

		if($committeId === '1')
		{
			if($associationId === '0')
			{
				$_SESSION['notify_error_add_membership'] = "Select an association if committee is StuRa.";
				
				header('Status: 400'); // Status bad request
				header('Location: ../'.urlencode('memberships.php'));
				exit;	
			}
			else 
			{
				$result = $db->addMembershipCustomPeriodCommitteeStuRa($memberId, $customReason, $customDateStartRequired, $customDateEndRequired, $committeId, $associationId);

				$_SESSION['notify_success_add_membership'] = "Membership added (custom period, committee (StuRa), association)";
			}
		}
		elseif ($committeId === '0' && $associationId != '0')
		{
			$result = $db->addMembershipCustomPeriodAssociation($memberId, $customReason, $customDateStartRequired, $customDateEndRequired, $associationId);

			$_SESSION['notify_success_add_membership'] = "Membership added (custom period, association)";
		}
		elseif ($committeId != '1' && $associationId === '0')
		{
			$result = $db->addMembershipCustomPeriodCommittee($memberId, $customReason, $customDateStartRequired, $customDateEndRequired, $committeId);

			$_SESSION['notify_success_add_membership'] = "Membership added (custom period, committee)";
		}
		else
		{
			$_SESSION['notify_error_add_membership'] = "Only select association if committee is 'StuRa'.";
				
			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('memberships.php'));
			exit;
		}
	}
	else 
	{
		$periodId = $_POST['selectPeriod'];

		if($committeId === '1')
		{
			if($associationId === '0')
			{
				$_SESSION['notify_error_add_membership'] = "Select an association if committee is StuRa.";
				
				header('Status: 400'); // Status bad request
				header('Location: ../'.urlencode('memberships.php'));
				exit;	
			}
			else 
			{
				$result = $db->addMembershipNormalPeriodCommitteeStuRa($memberId, $periodId, $committeId, $associationId);

				$_SESSION['notify_success_add_membership'] = "Membership added (normal period, committee (StuRa), association)";
			}
		}
		elseif ($committeId === '0' && $associationId != '0')
		{
			$result = $db->addMembershipNormalPeriodAssociation($memberId, $periodId, $associationId);

			$_SESSION['notify_success_add_membership'] = "Membership added (normal period, association)";
		}
		elseif ($committeId != '1' && $associationId === '0')
		{
			$result = $db->addMembershipNormalPeriodCommittee($memberId, $periodId, $committeId);

			$_SESSION['notify_success_add_membership'] = "Membership added (normal period, committee)";
		}
		else
		{
			$_SESSION['notify_error_add_membership'] = "Only select association if committee is 'StuRa'.";
				
			header('Status: 400'); // Status bad request
			header('Location: ../'.urlencode('memberships.php'));
			exit;
		}
	}
		
    if ($result) 
	{
		$db->commit();
			
		header('Status: 201'); // Status created
		header('Location: ../'.urlencode('memberships.php'));
		exit;

    } 
	else 
	{
		$db->rollback();

		$_SESSION['notify_error_add_membership'] = "Fatal error. Contact admin.";
		
		header('Status: 400'); // Status bad request
		header('Location: ../'.urlencode('memberships.php'));
		exit;
    }
}
 ?>