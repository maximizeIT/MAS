<?php
// needed for prepared DB statements
include_once('db/DBHandler_rw.php');

// Checks for input fields
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// check for errors
}

//Check all fields
if (empty($_POST['username_old']) || empty($_POST['username_new']) || empty($_POST['user_role']) || $_SERVER['REQUEST_METHOD'] == 'GET')
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

	$usernameOrg = $_POST['username_old'];
	$usernameNew = $_POST['username_new'];
	$role = $_POST['user_role'];

	$userId = $db->getUserId($usernameOrg);

	$result = $db->updateUser($userId, $usernameNew, $role);
		
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