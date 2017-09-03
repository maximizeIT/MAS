<?php

// Error Reporting komplett abschalten
error_reporting(0);

session_start();

if ( !isset($_SESSION['user_login_string']) || !isset($_SESSION['user_role']) || !isset($_SESSION['user_username']) ) 
{
    $_SESSION['notify_error_user'] = "Don't know what's happening... Maybe login first.";

    // redirect to login page
    header('Status: 401'); // Status unauthorized
    header('Location: ../'.urlencode('index.php'));
    exit;
}
elseif ( isset($_SESSION['user_login_string']) && isset($_SESSION['user_role'])  && isset($_SESSION['user_username']) ) 
{
    if ($_SESSION['user_role'] === "Employee") {
        // redirect to search interface
        header('Status: 200'); // Status ok
        header('Location: ../search/'.urlencode('index.php'));
        exit;
    }
    elseif ($_SESSION['user_role'] !== "Admin") {
        // Login failed
        $_SESSION['notify_error_user'] = "Don't know what's happening... Maybe login first.";

        // redirect to login page
        header('Status: 401'); // Status unauthorized
        header('Location: ../'.urlencode('index.php'));
        exit;
    }
}

include_once('assets/db/DBHandler_rw.php');

// Prevention CSRF attacks
include_once('assets/includes/nocsrf.php');

// new db instance
$db = new DbHandler();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0" name="viewport"/>
        <meta content="Membership Administration Software (MAS) 1.0" name="description"/>
        <meta content="Max Scholz" name="author"/>
        <title>
            MAS 1.0 | Data Management
        </title>
        <!-- CSS STYLES -->
        <link href="../assets/css/bundle.css" rel="stylesheet"/>
        <!-- MATERIAL DESIGN STYLES -->
        <link href="../assets/md-layout/material-design/css/material.css" rel="stylesheet"/>
        <link href="../assets/md-layout/css/layout.css" rel="stylesheet"/>
        <!-- PAGE STYLES -->
        <link href="../assets/plugins/datatables/dataTables.min.css" rel="stylesheet"/>
        </script>
    </head>
    <!-- BEGIN BODY -->
    <body class="fixed-topbar fixed-sidebar theme-sltl color-green">
        <!--[if lt IE 7]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div aria-hidden="true" class="modal fade" id="about" role="dialog" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-primary">
                    <div class="modal-header">
                        <button aria-hidden="true" class="close" data-dismiss="modal" type="button">
                            <i class="icons-office-52">
                            </i>
                        </button>
                        <h4 class="modal-title f-18">
                            <strong>
                                About
                            </strong>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <p class="f-16">
                            This web application (<b>Membership Administration System (MAS) 1.0</b>) was developed prototypically for the exam project of the course Database and Web Techniques at the TU Chemnitz (summer semester 2016). 
                            <br/><br/>
                            <b>Description:</b> MAS 1.0 is a system (PoC) that is able to manage the members of the different boards and their periods of office. A search inferface allows for rich search options amongst the whole database. An administrative area is available for managing all members, committees, associations and system users.
                            <br/><br/>
                            <b>Student name:</b> Max Scholz
                            <br/>
                            <b>Email:</b> max.scholz@s2015.tu-chemnitz.de
                            <br/>
                            <b>Student ID:</b> 417462
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal" type="button">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <section>
            <!-- BEGIN SIDEBAR -->
            <div class="sidebar">
                <div class="sidebar-inner">
                    <div class="sidebar-top">
                        <div class="userlogged clearfix">
                            <i class="icon icons-faces-users-03">
                            </i>
                            <div class="user-details">
                                <h4>
                                    <?php echo htmlentities($_SESSION['user_username'], ENT_HTML5, 'UTF-8') ?>
                                </h4>
                                <?php echo htmlentities($_SESSION['user_role'], ENT_HTML5, 'UTF-8') ?>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-sidebar">
                        <li class="nav-parent">
                            <a href="">
                                <i class="fa fa-graduation-cap">
                                </i>
                                <span>
                                    Member Mgmt.
                                </span>
                                <span class="fa arrow">
                                </span>
                            </a>
                            <ul class="children collapse">
                                <li>
                                    <a href="index.php">
                                        Overview
                                    </a>
                                </li>
                                <li>
                                    <a href="members_add.php">
                                        Add Member(s)
                                    </a>
                                </li>
                                <li>
                                    <a href="members_edit.php">
                                        Edit Member(s)
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="memberships.php">
                                <i class="fa fa-certificate">
                                </i>
                                <span>
                                    Memberships
                                </span>
                            </a>
                        </li>
                        <li class="nav-active active">
                            <a href="data_mgmt.php">
                                <i class="fa fa-database">
                                </i>
                                <span>
                                    Data Mgmt.
                                </span>
                            </a>
                        </li>
                    </ul>
                    <!-- SIDEBAR FOOTER -->
                    <div class="sidebar-footer clearfix">
                        <div class="m-10 f-10 copyright">
                            <p>
                                <span>
                                    Copyright
                                    <span class="copyright">
                                        ©
                                    </span>
                                    2016
                                </span>
                                <span>
                                    Max Scholz.
                                </span>
                                <br/>
                                <span>
                                    All rights reserved.
                                </span>
                                <br/>
                                <span>
                                    <a data-target="#about" data-toggle="modal">
                                        About
                                    </a>
                                    | MAS v1.0
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END SIDEBAR -->
            <div class="main-content">
                <!-- BEGIN TOPBAR -->
                <div class="topbar">
                    <div class="header-left">
                        <div class="topnav">
                            <a class="menutoggle" data-toggle="sidebar-collapsed" href="#">
                                <span class="menu__handle">
                                    <span>
                                        Menu
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="header-right">
                        <ul class="header-menu nav navbar-nav">
                            <li>
                                <a class="toggle_fullscreen" data-original-title="Fullscreen" data-placement="bottom" data-rel="tooltip" href="#">
                                    <i class="icon-size-fullscreen">
                                    </i>
                                </a>
                            </li>
                            <li>
                                <a data-original-title="Logout" data-placement="bottom" data-rel="tooltip" href="../assets/scripts/logout.php?logout">
                                    <i class="icon-logout">
                                    </i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- header-right -->
                </div>
                <!-- END TOPBAR -->
                <!-- BEGIN PAGE CONTENT -->
                <div class="page-content page-thin">
                    <div class="header text-center">
                        <h2><strong>Data Management</strong></h2>
                    </div>
                    <!-- System Users -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-header md-panel-controls panel-init-open">
                                    <h3>
                                        <i class="fa fa-users">
                                        </i>
                                        <strong>
                                            System Users
                                        </strong>
                                    </h3>
                                </div>
                                <div class="panel-content">
                                    <p>
                                        Sort, filter or search all system users. You cann add, update (username, user role) and delete a system user.
                                    </p>
                                    <div class="panel-group panel-accordion" id="accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4>
                                                    <a class="collapsed" data-parent="#accordion" data-toggle="collapse" href="#collapseUser">
                                                        Add User
                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse" id="collapseUser">
                                                <div class="panel-body">
                                                    <form enctype="multipart/form-data" class="form-horizontal form-validation" method="POST" action="assets/addUser.php">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">
                                                                    Username
                                                                </label>
                                                                <div class="col-sm-9 prepend-icon">
                                                                    <input autocomplete="off" class="form-control" minlength="5" name="user_username" placeholder="Minimum 5 characters..." required="" type="text">
                                                                        <i class="icon-user">
                                                                        </i>
                                                                    </input>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">
                                                                    Password
                                                                </label>
                                                                <div class="col-sm-9 prepend-icon">
                                                                    <input autocomplete="off" class="form-control" id="password" maxlength="20" minlength="5" name="user_password" placeholder="Between 5 and 20 characters" required="" type="password">
                                                                        <i class="icon-lock">
                                                                        </i>
                                                                    </input>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">
                                                                    Role
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <select class="form-control" data-placeholder="Select a role..." required="" name="user_role">
                                                                        <option hidden="" value="">
                                                                            Please select a user role
                                                                        </option>
                                                                        <option value="Employee">
                                                                            Employee
                                                                        </option>
                                                                        <option value="Admin">
                                                                            Admin
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <?php 
                                                                    $tokenAddUser = NoCSRF::generate('csrf_token_add_user'); 
                                                                ?>
                                                                <input type="hidden" class="form-control" name="csrf_token_add_user" id="csrf_token_add_user" value="<?php echo $tokenAddUser ?>"/>
                                                            </div>
                                                            <div class="text-right">
                                                                <button class="cancel btn btn-embossed btn-default m-b-10 m-r-0" type="reset">
                                                                    Reset
                                                                </button>
                                                                <button class="btn btn-embossed btn-primary" type="submit">
                                                                    Add
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php

                                    if (isset($_SESSION['notify_success_add_user'])) {
                                        echo '
                                        <div class="col-md-12">
                                          <div class="md-alert alert alert-success alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                            <div class="media-body width-100p">
                                              <p class="md-title">Success</p>
                                              <div class="md-txt">
                                                <p>' . htmlentities($_SESSION['notify_success_add_user'], ENT_HTML5, 'UTF-8') . '</p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>';
                                        unset($_SESSION['notify_success_add_user']);
                                    }
                                    elseif (isset($_SESSION['notify_error_add_user'])) {
                                        echo '
                                        <div class="col-md-12">
                                          <div class="md-alert alert alert-danger alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                            <div class="media-body width-100p">
                                              <p class="md-title">Error</p>
                                              <div class="md-txt">
                                                <p>' . htmlentities($_SESSION['notify_error_add_user'], ENT_HTML5, 'UTF-8') . '</p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>';
                                        unset($_SESSION['notify_error_add_user']);
                                    }
                                    ?>
                                    <div class="row p-20">
                                        <!-- USERS TABLE -->
                                        <table class="table table-hover dataTable" id="table-editable-users">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Username
                                                    </th>
                                                    <th>
                                                        Password (hashed)
                                                    </th>
                                                    <th>
                                                        User Role
                                                    </th>
                                                    <th>
                                                        Timestamp Added
                                                    </th>
                                                    <th class="text-right">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php

                                                // run query
                                                $result = $db->getAllSystemUsers();

                                                if(count($result) > 0)
                                                {
                                                    for ($x = 0; $x < count($result); $x++)
                                                    {  
                                                        $row = $result[$x];
                                                        
                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    ".htmlentities($row['username'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['password_hash'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['user_role'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['timestamp_added'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td class='text-right'>
                                                                    <a class='edit btn btn-sm btn-default' href='javascript:;'>
                                                                        <i class='icon-note'>
                                                                        </i>
                                                                    </a>
                                                                    <a class='delete btn btn-sm btn-danger' href='javascript:;'>
                                                                        <i class='icons-office-52'>
                                                                        </i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "
                                                        <tr>
                                                            <td>
                                                                No system users found.
                                                            </td>
                                                            <td>
                                                            </td>
                                                            <td>
                                                            </td>
                                                            <td>
                                                            </td>
                                                            <td>
                                                            </td>
                                                        </tr>
                                                    ";
                                                }
                                                
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Committees -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-header md-panel-controls panel-init-open">
                                    <h3>
                                        <i class="fa fa-building">
                                        </i>
                                        <strong>
                                            Committees
                                        </strong>
                                    </h3>
                                </div>
                                <div class="panel-content">
                                    <p>
                                        Sort, filter or search all committees. You cann add, update (name, description, date founded) and delete a committee.
                                    </p>
                                    <div class="panel-group panel-accordion" id="accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4>
                                                    <a class="collapsed" data-parent="#accordion" data-toggle="collapse" href="#collapseCommittee">
                                                        Add Committee
                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse" id="collapseCommittee">
                                                <div class="panel-body">
                                                    <form enctype="multipart/form-data" class="form-horizontal form-validation" method="POST" action="assets/addCommittee.php">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">
                                                                    Name
                                                                </label>
                                                                <div class="col-sm-9 prepend-icon">
                                                                    <input autocomplete="off" class="form-control" id="committee_name" maxlength="50" minlength="5" name="committee_name" placeholder="Between 5 and 50 characters" required="" type="text">
                                                                        <i class="fa fa-info-circle">
                                                                        </i>
                                                                    </input>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">
                                                                    Description
                                                                </label>
                                                                <div class="col-sm-9 append-icon">
                                                                    <textarea autocomplete="off" class="form-control" data-hint="Minimum of 15 and maximum of 250 characters..." id="committee_description" maxlength="250" minlength="15" name="committee_description" placeholder="Minimum 15 characters..." rows="4">
                                                                    </textarea>
                                                                    <i class="fa fa-flag">
                                                                    </i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <?php 
                                                                    $tokenAddCommittee = NoCSRF::generate('csrf_token_add_committee'); 
                                                                ?>
                                                                <input type="hidden" class="form-control" name="csrf_token_add_committee" id="csrf_token_add_committee" value="<?php echo $tokenAddCommittee ?>"/>
                                                            </div>
                                                            <div class="text-center">
                                                                <button class="cancel btn btn-embossed btn-default m-b-10 m-r-0" type="reset">
                                                                    Reset
                                                                </button>
                                                                <button class="btn btn-embossed btn-primary" type="submit">
                                                                    Add
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php

                                    if (isset($_SESSION['notify_success_add_committee'])) {
                                        echo '
                                        <div class="col-md-12">
                                          <div class="md-alert alert alert-success alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                            <div class="media-body width-100p">
                                              <p class="md-title">Success</p>
                                              <div class="md-txt">
                                                <p>' . htmlentities($_SESSION['notify_success_add_committee'], ENT_HTML5, 'UTF-8') . '</p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>';
                                        unset($_SESSION['notify_success_add_committee']);
                                    }
                                    elseif (isset($_SESSION['notify_error_add_committee'])) {
                                        echo '
                                        <div class="col-md-12">
                                          <div class="md-alert alert alert-danger alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                            <div class="media-body width-100p">
                                              <p class="md-title">Error</p>
                                              <div class="md-txt">
                                                <p>' . htmlentities($_SESSION['notify_error_add_committee'], ENT_HTML5, 'UTF-8') . '</p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>';
                                        unset($_SESSION['notify_error_add_committee']);
                                    }
                                    ?>
                                    <div class="row p-20">
                                        <!-- COMMITTEES TABLE -->
                                        <table class="table table-hover dataTable" id="table-editable-committees">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Name
                                                    </th>
                                                    <th>
                                                        Description
                                                    </th>
                                                    <th>
                                                        Timestamp Added
                                                    </th>
                                                    <th class="text-right">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php

                                                // run query
                                                $result = $db->getAllCommittees();

                                                if(count($result) > 0)
                                                {
                                                    for ($x = 0; $x < count($result); $x++)
                                                    {  
                                                        $row = $result[$x];
                                                        
                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    ".htmlentities($row['name'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['description'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['timestamp_added'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td class='text-right'>
                                                                    <a class='edit btn btn-sm btn-default' href='javascript:;'>
                                                                        <i class='icon-note'>
                                                                        </i>
                                                                    </a>
                                                                    <a class='delete btn btn-sm btn-danger' href='javascript:;'>
                                                                        <i class='icons-office-52'>
                                                                        </i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "
                                                        <tr>
                                                            <td>
                                                                No committees found.
                                                            </td>
                                                            <td>
                                                            </td>
                                                            <td>
                                                            </td>
                                                            <td>
                                                            </td>
                                                        </tr>
                                                    ";
                                                }         
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Associations -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-header md-panel-controls panel-init-open">
                                    <h3>
                                        <i class="fa fa-university">
                                        </i>
                                        <strong>
                                            Associations
                                        </strong>
                                    </h3>
                                </div>
                                <div class="panel-content">
                                    <p>
                                        Sort, filter or search all associations. You cann add, update (name, website) and delete an associations.
                                    </p>
                                    <div class="panel-group panel-accordion" id="accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4>
                                                    <a class="collapsed" data-parent="#accordion" data-toggle="collapse" href="#collapseAssociation">
                                                        Add Association
                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse" id="collapseAssociation">
                                                <div class="panel-body">
                                                    <form enctype="multipart/form-data" class="form-horizontal form-validation" method="POST" action="assets/addAssociation.php">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">
                                                                    Name
                                                                </label>
                                                                <div class="col-sm-9 prepend-icon">
                                                                    <input autocomplete="off" class="form-control" maxlength="50" minlength="5" name="association_name" placeholder="Between 5 and 50 characters" required="" type="text">
                                                                        <i class="fa fa-info-circle">
                                                                        </i>
                                                                    </input>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">
                                                                    Website
                                                                </label>
                                                                <div class="col-sm-9 prepend-icon">
                                                                    <input autocomplete="off" class="form-control" name="association_website" placeholder="URL..." type="url">
                                                                        <i class="fa fa-globe">
                                                                        </i>
                                                                    </input>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <?php 
                                                                    $tokenAddAssociation = NoCSRF::generate('csrf_token_add_association'); 
                                                                ?>
                                                                <input type="hidden" class="form-control" name="csrf_token_add_association" id="csrf_token_add_association" value="<?php echo $tokenAddAssociation ?>"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="text-center">
                                                                <button class="cancel btn btn-embossed btn-default m-b-10 m-r-0" type="reset">
                                                                    Reset
                                                                </button>
                                                                <button class="btn btn-embossed btn-primary" type="submit">
                                                                    Add
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php

                                    if (isset($_SESSION['notify_success_add_association'])) {
                                        echo '
                                        <div class="col-md-12">
                                          <div class="md-alert alert alert-success alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                            <div class="media-body width-100p">
                                              <p class="md-title">Success</p>
                                              <div class="md-txt">
                                                <p>' . htmlentities($_SESSION['notify_success_add_association'], ENT_HTML5, 'UTF-8') . '</p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>';
                                        unset($_SESSION['notify_success_add_association']);
                                    }
                                    elseif (isset($_SESSION['notify_error_add_association'])) {
                                        echo '
                                        <div class="col-md-12">
                                          <div class="md-alert alert alert-danger alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                            <div class="media-body width-100p">
                                              <p class="md-title">Error</p>
                                              <div class="md-txt">
                                                <p>' . htmlentities($_SESSION['notify_error_add_association'], ENT_HTML5, 'UTF-8') . '</p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>';
                                        unset($_SESSION['notify_error_add_association']);
                                    }
                                    ?>
                                    <div class="row p-20">
                                        <!-- COMMITTEES TABLE -->
                                        <table class="table table-hover dataTable" id="table-editable-associations">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Name
                                                    </th>
                                                    <th>
                                                        Website
                                                    </th>
                                                    <th>
                                                        Timestamp Added
                                                    </th>
                                                    <th class="text-right">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php

                                                // run query
                                                $result = $db->getAllAssociations();

                                                if(count($result) > 0)
                                                {
                                                    for ($x = 0; $x < count($result); $x++)
                                                    {  
                                                        $row = $result[$x];
                                                        
                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    ".htmlentities($row['name'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['website'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['timestamp_added'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td class='text-right'>
                                                                    <a class='edit btn btn-sm btn-default' href='javascript:;'>
                                                                        <i class='icon-note'>
                                                                        </i>
                                                                    </a>
                                                                    <a class='delete btn btn-sm btn-danger' href='javascript:;'>
                                                                        <i class='icons-office-52'>
                                                                        </i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "
                                                        <tr>
                                                            <td>
                                                                No associations found.
                                                            </td>
                                                            <td>
                                                            </td>
                                                            <td>
                                                            </td>
                                                            <td>
                                                            </td>
                                                        </tr>
                                                    ";
                                                }         
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Periods of Office -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-header md-panel-controls panel-init-open">
                                    <h3>
                                        <i class="fa fa-clock-o">
                                        </i>
                                        <strong>
                                            Periods of Office
                                        </strong>
                                    </h3>
                                </div>
                                <div class="panel-content">
                                    <p>
                                        
                                    </p>
                                    <div class="panel-group panel-accordion" id="accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4>
                                                    <a class="collapsed" data-parent="#accordion" data-toggle="collapse" href="#collapseOfficePeriods">
                                                        Add Period of Office
                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse" id="collapseOfficePeriods">
                                                <div class="panel-body">
                                                    <form enctype="multipart/form-data" class="form-horizontal form-validation" method="POST" action="assets/addOfficePeriod.php">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">
                                                                    Start
                                                                </label>
                                                                <div class="col-sm-9 prepend-icon">
                                                                    <input autocomplete="off" class="b-datepicker form-control" name="period_date_starts" placeholder="Select a date..." type="text" required="" data-view="2">
                                                                        <i class="icon-calendar">
                                                                        </i>
                                                                    </input>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label">
                                                                    End
                                                                </label>
                                                                <div class="col-sm-9 prepend-icon">
                                                                    <input autocomplete="off" class="b-datepicker form-control" name="period_date_ends" placeholder="Select a date..." type="text" required="" data-view="2">
                                                                        <i class="icon-calendar">
                                                                        </i>
                                                                    </input>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <?php 
                                                                    $tokenAddOfficePeriod = NoCSRF::generate('csrf_token_add_officeperiod'); 
                                                                ?>
                                                                <input type="hidden" class="form-control" name="csrf_token_add_officeperiod" id="csrf_token_add_officeperiod" value="<?php echo $tokenAddOfficePeriod ?>"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="text-center">
                                                                <button class="cancel btn btn-embossed btn-default m-b-10 m-r-0" type="reset">
                                                                    Reset
                                                                </button>
                                                                <button class="btn btn-embossed btn-primary" type="submit">
                                                                    Add
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php

                                    if (isset($_SESSION['notify_success_add_officeperiod'])) {
                                        echo '
                                        <div class="col-md-12">
                                          <div class="md-alert alert alert-success alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                            <div class="media-body width-100p">
                                              <p class="md-title">Success</p>
                                              <div class="md-txt">
                                                <p>' . htmlentities($_SESSION['notify_success_add_officeperiod'], ENT_HTML5, 'UTF-8') . '</p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>';
                                        unset($_SESSION['notify_success_add_officeperiod']);
                                    }
                                    elseif (isset($_SESSION['notify_error_add_officeperiod'])) {
                                        echo '
                                        <div class="col-md-12">
                                          <div class="md-alert alert alert-danger alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                            <div class="media-body width-100p">
                                              <p class="md-title">Error</p>
                                              <div class="md-txt">
                                                <p>' . htmlentities($_SESSION['notify_error_add_officeperiod'], ENT_HTML5, 'UTF-8') . '</p>
                                              </div>
                                            </div>
                                          </div>
                                        </div>';
                                        unset($_SESSION['notify_error_add_officeperiod']);
                                    }
                                    ?>
                                    <div class="row p-20">
                                        <!-- COMMITTEES TABLE -->
                                        <table class="table table-hover dataTable" id="table-editable-periods">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Period ID
                                                    </th>
                                                    <th>
                                                        Period Starts (YYYY/MM/DD)
                                                    </th>
                                                    <th>
                                                        Period Ends (YYYY/MM/DD)
                                                    </th>
                                                    <th class="text-right">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php

                                                // run query
                                                $result = $db->getAllOfficePeriods();

                                                if(count($result) > 0)
                                                {
                                                    for ($x = 0; $x < count($result); $x++)
                                                    {  
                                                        $row = $result[$x];
                                                        
                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    ".htmlentities($row['period_id'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['date_starts'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['date_ends'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td class='text-right'>
                                                                    <a class='edit btn btn-sm btn-default' href='javascript:;'>
                                                                        <i class='icon-note'>
                                                                        </i>
                                                                    </a>
                                                                    <a class='delete btn btn-sm btn-danger' href='javascript:;'>
                                                                        <i class='icons-office-52'>
                                                                        </i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "
                                                        <tr>
                                                            <td>
                                                                No periods of office found.
                                                            </td>
                                                            <td>
                                                            </td>
                                                            <td>
                                                            </td>
                                                            <td>
                                                            </td>
                                                        </tr>
                                                    ";
                                                }         
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FOOTER -->
                    <div class="footer m-t-60">
                        <div class="copyright">
                          <p class="pull-left sm-pull-reset">
                            <span>Copyright <span class="copyright">©</span> 2016 </span>
                            <span>Max Scholz</span>.
                            <span>All rights reserved. </span>
                          </p>
                          <p class="pull-right sm-pull-reset">
                            <span>
                                <a data-target="#about" data-toggle="modal">
                                    About
                                </a>
                                | MAS v1.0
                            </span>
                          </p>
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT -->
            </div>
            <!-- END MAIN CONTENT -->
        </section>
        <!-- BEGIN PRELOADER -->
        <div class="loader-overlay">
            <div class="spinner">
                <div class="bounce1">
                </div>
                <div class="bounce2">
                </div>
                <div class="bounce3">
                </div>
            </div>
        </div>
        <!-- END PRELOADER -->
        <a class="scrollup" href="#">
            <i class="fa fa-angle-up">
            </i>
        </a>
    </body>
</html>
<!--  
**  LOCAL
-->
<!-- JQUERY -->
<script src="../assets/plugins/jquery/jquery-1.11.1.min.js">
</script>
<script src="../assets/plugins/jquery/jquery-migrate-1.2.1.min.js">
</script>
<script src="../assets/plugins/jquery-ui/jquery-ui-1.11.2.min.js">
</script>
<script src="../assets/plugins/jquery-validation/jquery.validate.min.js">
</script>
<script src="../assets/plugins/jquery-validation/additional-methods.min.js">
</script>
<script src="../assets/plugins/jquery-cookies/jquery.cookies.min.js">
</script>
<!-- MATERIAL DESIGN PLUGINS -->
<script src="../assets/md-layout/material-design/js/material.js">
</script>
<!-- HELPER PLUGINS -->
<!-- Datepicker -->
<script src="../assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js">
</script>
<!-- Checkbox & Radio Inputs -->
<script src="../assets/plugins/icheck/icheck.min.js">
</script>
<!-- Select Inputs -->
<script src="../assets/plugins/select2/select2.min.js">
</script>
<!-- Logout User After Delay -->
<script src="../assets/plugins/idle-timeout/jquery.idletimer.min.js">
</script>
<!-- Logout User After Delay -->
<script src="../assets/plugins/idle-timeout/jquery.idletimeout.min.js">
</script>
<!-- Tables Filtering, Sorting & Editing -->
<script src="../assets/plugins/datatables/jquery.dataTables.min.js">
</script>
<!-- CUSTOM SCRIPTS -->
<script src="../assets/js/application.js">
</script>
<script src="../assets/js/plugins.js">
</script>
<script src="../assets/js/layout.js">
</script>
<script src="../assets/js/sessionTimeout.js">
</script>
<!--  
**  EXTERNAL
-->
<!-- BOOTSTRAP -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js">
</script>
<script>
    $.material.init();
</script>