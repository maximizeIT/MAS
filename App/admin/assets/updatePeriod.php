<?php
// needed for prepared DB statements
include_once('db/DBHandler_rw.php');

// Checks for input fields
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// check for errors
}

//Check all fields
if (empty($_POST['period_id']) || empty($_POST['period_date_starts']) || empty($_POST['period_date_ends']) || $_SERVER['REQUEST_METHOD'] == 'GET')
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

	$period_id = $_POST['period_id'];

	$date_start = $_POST['period_date_starts'];
	$date_end = $_POST['period_date_ends'];

  	$dateStartRequired = date("Y-m-d", strtotime($date_start));
  	$dateEndRequired = date("Y-m-d", strtotime($date_end));

	$result = $db->updateOfficePeriod($period_id, $dateStartRequired, $dateEndRequired);
		
    if ($result) 
	{
		$db->commit();
			
		header('Status: 201'); // Status created
		header('Location: ../'.urlencode('data_mgmt.php'));
		exit;

    } 
	else 
	{
		$db->rollback();
		
		header('Status: 400'); // Status bad request
		header('Location: ../'.urlencode('data_mgmt.php'));
		exit;
    }
}
 ?>