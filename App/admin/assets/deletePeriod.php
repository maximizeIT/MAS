<?php
// needed for prepared DB statements
include_once('db/DBHandler_rw.php');

// Checks for input fields
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// check for errors
}

//Check all fields
if (empty($_POST['period_id']) || $_SERVER['REQUEST_METHOD'] == 'GET')
{
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
   	{
    	
   	}
}
else // delete single member
{	
	// Create connection
	$db = new DbHandler();
	
	$db->start_transaction();

	$periodId = $_POST['period_id'];
	
	$result = $db->deleteOfficePeriod($periodId);
		
	if($result)
	{
		$db->commit();
		
		header('Status: 200'); // status ok
		header('Location: ../'.urlencode('data_mgmt.php'));

		exit;
	}
	else
	{
		$db->rollback();
		
		header('Status: 400'); // status bad request
		header('Location: ../'.urlencode('data_mgmt.php'));

		exit;
	}
}
 ?>