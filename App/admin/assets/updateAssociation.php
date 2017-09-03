<?php
// needed for prepared DB statements
include_once('db/DBHandler_rw.php');

// Checks for input fields
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// check for errors
}

//Check all fields
if (empty($_POST['association_old']) || empty($_POST['association_new']) || $_SERVER['REQUEST_METHOD'] == 'GET')
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

	$associationOld = $_POST['association_old'];
	$associationName = $_POST['association_new'];
	$associationWebsite = $_POST['association_website'];

	$associationId = $db->getAssociationId($associationOld);

	$result = $db->updateAssociation($associationId, $associationName, $associationWebsite);
		
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