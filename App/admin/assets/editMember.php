<?php
// needed for prepared DB statements
include_once('db/DBHandler_rw.php');

// Prevention CSRF attacks
include_once('includes/nocsrf.php');

// Checks for input fields
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (empty($_GET['memberId'])) {
        $err = "Member ID not provided. Please try again.";
    }
}

// Check all fields
if (empty($_GET["memberId"]) || !empty($err) || $_SERVER['REQUEST_METHOD'] == 'POST')
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        if (!empty($err)) {
            $_SESSION['notify_error_update_member'] = $err;
            header('Status: 400'); // Status bad request
            header('Location: ../'.urlencode('members_edit.php'));
            exit;
        } 
    }
    else {
        $_SESSION['notify_error_update_member'] = 'What are you trying mate?';
        header('Status: 400'); // Status bad request
        header('Location: ../'.urlencode('members_edit.php'));
        exit;
    }
}
else
{
    // Create connection
    $db = new DbHandler();

    // get the member email parameter from URL
    $id = $_GET["memberId"];

    $resultArr = $db->getSingleMember($id);
    
    if(count($resultArr) > 0)
    {
        header('Content-type: application/json');
        echo json_encode($resultArr[0]);
    }
    else
    {
        header('Content-type: application/json');
        echo json_encode(null);
    }
}
?>