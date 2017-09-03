<?php
// needed for prepared DB statements
include_once('db/DBHandler_rw.php');

// Checks for input fields
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// check for errors
}

//Check all fields
if (empty($_POST['committee_old']) || empty($_POST['committee_new']) || $_SERVER['REQUEST_METHOD'] == 'GET')
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
   	{
    	
   	}
}
else
{	
	// Create connection
	$db = new DbHandler();
	
	$db->start_transaction();

	$committeeOld = $_POST['committee_old'];
	$committeeName = $_POST['committee_new'];
	$committeeDescription = $_POST['committee_desc'];

	$committeeId = $db->getCommitteeId($committeeOld);

	$result = $db->updateCommittee($committeeId, $committeeName, $committeeDescription);
		
    if ($result) 
	{
		$db->commit();
			
		header('Status: 201'); // Status created
		header('Location: ../'.urlencode('index.php'));
		exit;

    } 
	else 
	{
		$db->rollback();
		
		header('Status: 400'); // Status bad request
		header('Location: ../'.urlencode('index.php'));
		exit;
    }
}
 ?>