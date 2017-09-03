<?php
// Error Reporting komplett abschalten
error_reporting(0);

session_start();

if (isset($_GET['logout'])) {

	session_destroy();

	unset($_SESSION['user_username']);
	unset($_SESSION['user_role']);
	unset($_SESSION['user_login_string']);

	header('Status: 200'); // Status ok
	header("Location: ../../index.php");
	exit;
} 
else 
{
	header('Status: 200'); // Status ok
  header("Location: ../../index.php");
  exit;
}

?>