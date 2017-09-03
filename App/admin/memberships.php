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
            MAS 1.0 | Memberships Administration
        </title>
        <!-- CSS STYLES -->
        <link href="../assets/css/bundle.css" rel="stylesheet"/>
        <!-- MATERIAL DESIGN STYLES -->
        <link href="../assets/md-layout/material-design/css/material.css" rel="stylesheet"/>
        <link href="../assets/md-layout/css/layout.css" rel="stylesheet"/>
        <!-- PAGE STYLES -->
        <link href="../assets/plugins/datatables/dataTables.min.css" rel="stylesheet"/>
        <!-- EXTERNAL -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js">
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
                        <li class="active">
                            <a href="memberships.php">
                                <i class="fa fa-certificate">
                                </i>
                                <span>
                                    Memberships
                                </span>
                            </a>
                        </li>
                        <li>
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
                        <h2><strong>Memberships Administration</strong></h2>
                    </div>
                    <?php

                    if (isset($_SESSION['notify_success_add_membership'])) {
                        echo '
                        <div class="col-md-12">
                          <div class="md-alert alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                            <div class="media-body width-100p">
                              <p class="md-title">Success</p>
                              <div class="md-txt">
                                <p>' . htmlentities($_SESSION['notify_success_add_membership'], ENT_HTML5, 'UTF-8') . '</p>
                              </div>
                            </div>
                          </div>
                        </div>';
                        unset($_SESSION['notify_success_add_membership']);
                    }
                    elseif (isset($_SESSION['notify_error_add_membership'])) {
                        echo '
                        <div class="col-md-12">
                          <div class="md-alert alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                            <div class="media-body width-100p">
                              <p class="md-title">Error</p>
                              <div class="md-txt">
                                <p>' . htmlentities($_SESSION['notify_error_add_membership'], ENT_HTML5, 'UTF-8') . '</p>
                              </div>
                            </div>
                          </div>
                        </div>';
                        unset($_SESSION['notify_error_add_membership']);
                    }
                    elseif (isset($_SESSION['notify_success_delete_membership'])) {
                        echo '
                        <div class="col-md-12">
                          <div class="md-alert alert alert-success alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                            <div class="media-body width-100p">
                              <p class="md-title">Success</p>
                              <div class="md-txt">
                                <p>' . htmlentities($_SESSION['notify_success_delete_membership'], ENT_HTML5, 'UTF-8') . '</p>
                              </div>
                            </div>
                          </div>
                        </div>';
                        unset($_SESSION['notify_success_delete_membership']);
                    }
                    elseif (isset($_SESSION['notify_error_delete_membership'])) {
                        echo '
                        <div class="col-md-12">
                          <div class="md-alert alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                            <div class="media-body width-100p">
                              <p class="md-title">Error</p>
                              <div class="md-txt">
                                <p>' . htmlentities($_SESSION['notify_error_delete_membership'], ENT_HTML5, 'UTF-8') . '</p>
                              </div>
                            </div>
                          </div>
                        </div>';
                        unset($_SESSION['notify_error_delete_membership']);
                    }
                    ?>
                    <div class="panel-body">
                        <form enctype="multipart/form-data" class="form-horizontal form-validation" role="form" novalidate="novalidate" method="POST" action="assets/addMembership.php">
                            <p>
                                First select a member from the dropdown list...
                            </p>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Member</label>
                                    <div class="col-sm-9">
                                        <div class="option-group">
                                            <select class="form-control selectMember" data-placeholder="Select a member..." required id="selectMember" name="selectMember" aria-required="true">
                                                <?php
                                                    // run query
                                                    $result = $db->getAllMembersPersonal();

                                                    if(count($result) > 0)
                                                    {
                                                        echo "<option selected hidden='' value='0'>Select one member...</option>";

                                                        for ($x = 0; $x < count($result); $x++)
                                                        {  
                                                            $row = $result[$x];       
                                                            echo "<option value=".htmlentities($row['member_id'], ENT_HTML5, 'UTF-8').">
                                                            
                                                            " . htmlentities($row['fname'], ENT_HTML5, 'UTF-8') . " " . htmlentities($row['lname'], ENT_HTML5, 'UTF-8')." (ID: ".htmlentities($row['member_id'], ENT_HTML5, 'UTF-8')." -- Email: ".htmlentities($row['email'], ENT_HTML5, 'UTF-8') .")</option>"; 
                                                        }
                                                        echo "</select>";
                                                    }
                                                    else
                                                    {
                                                        echo "<option selected disabled hidden='' value=''>No members found.</option>"; 
                                                        echo "</select>";   
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p>
                                Next select a period of office from the dropdown list...
                            </p>
                            <div class="col-md-12" id="inputsPeriods">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Period of Office</label>
                                    <div class="col-sm-9">
                                        <div class="option-group">
                                            <select class="form-control selectPeriod" data-placeholder="Select a member..." required="" id="selectPeriod" name="selectPeriod" aria-required="true">
                                                <?php
                                                    // run query
                                                    $result = $db->getAllOfficePeriods();

                                                    if(count($result) > 0)
                                                    {
                                                        echo "<option hidden='' id='default' value='0'>Select a period of office (or enter a custom one due to specific reason)...</option>";

                                                        for ($x = 0; $x < count($result); $x++)
                                                        {  
                                                            $row = $result[$x];
                                                            
                                                            $start = strtotime($row['date_starts']);
                                                            $end = strtotime($row['date_ends']);
                                                            
                                                            $dateStart = date("jS \of F Y", $start);
                                                            $dateEnd = date("jS \of F Y", $end);

                                                            echo "<option value=".htmlentities($row['period_id'], ENT_HTML5, 'UTF-8').">". htmlentities($dateStart, ENT_HTML5, 'UTF-8') ." till ". htmlentities($dateEnd, ENT_HTML5, 'UTF-8') ."
                                                            </option>"; 
                                                        }
                                                        echo "</select>";
                                                    }
                                                    else
                                                    {
                                                        echo "<option selected disabled hidden='' value=''>No periods of office found.</option>"; 
                                                        echo "</select>";   
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p>
                                ... or enter a custom period of office with a reason.
                            </p>
                            <div class="col-md-12" id="inputsCustom">
                                <div class="form-group">
                                  
                                  <label class="col-sm-3 control-label">Custom Period</label>
                                  <div class="col-sm-9">
                                    <div class="checkbox checkbox-primary p-t-0">
                                     <label>
                                      <label>
                                        <input type="checkbox" name="checkboxCustomPeriod" id="checkboxCustomPeriod" class="md-checkbox"><span class="checkbox-material"><span class="check"></span></span>
                                      </label>
                                    </div>
                                  </div>
                                </div>
                            </div>

                            <div id="customPeriodFields">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            Reason
                                        </label>
                                        <div class="col-sm-9 after-tooltip">
                                            <input type="text" id="selectCustomReason" name="selectCustomReason" class="form-control" placeholder="Enter a reason...">
                                            <i class="fa fa-question-circle c-blue" rel="popover" data-container="body" data-toggle="popover" data-placement="left" data-content="e.g. successor, late-comer"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-12 control-label">
                                            Select custom period start and end date:
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            Start
                                        </label>
                                        <div class="col-sm-9 prepend-icon">
                                            <input autocomplete="off" class="b-datepicker form-control" name="selectCustomStart" placeholder="Select a date..." type="text" data-view="2" id="selectCustomStart">
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
                                            <input autocomplete="off" class="b-datepicker form-control" name="selectCustomEnd" placeholder="Select a date..." type="text" data-view="2" id="selectCustomEnd">
                                                <i class="icon-calendar">
                                                </i>
                                            </input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p>
                                Now select the committee or association the member is associated with.
                            </p>
                            <div class="col-md-12" id="inputsCommittee">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Committee</label>
                                    <div class="col-sm-9">
                                        <div class="option-group">
                                            <select class="form-control selectCommittee" data-placeholder="Select a committee..." required="" id="selectCommittee" name="selectCommittee" aria-required="true">
                                            <?php
                                                // run query
                                                $result = $db->getAllCommittees();

                                                if(count($result) > 0)
                                                {
                                                    echo "<option selected hidden='' value='0'>Select a committee...</option>";

                                                    for ($x = 0; $x < count($result); $x++)
                                                    {  
                                                        $row = $result[$x];       
                                                        echo "<option value=".htmlentities($row['committee_id'], ENT_HTML5, 'UTF-8').">
                                                        
                                                        " . htmlentities($row['name'], ENT_HTML5, 'UTF-8') . "</option>"; 
                                                    }
                                                    echo "</select>";
                                                }
                                                else
                                                {
                                                    echo "<option selected disabled hidden='' value='0'>No committees found.</option>"; 
                                                    echo "</select>";   
                                                }
                                            ?>
                                            </select>
                                        </div>
                                        <p class="label label-primary">Note: If committee is StuRa, then association is required.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                  <label class="col-sm-3 control-label">OR</label>
                                </div>
                            </div>

                            <div class="col-md-12" id="inputsAssociation">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Association</label>
                                    <div class="col-sm-9">
                                        <div class="option-group">
                                            <select class="form-control" data-placeholder="Select an association..." required="" id="selectAssociation" aria-required="true" name="selectAssociation">
                                            <?php
                                                // run query
                                                $result = $db->getAllAssociations();

                                                if(count($result) > 0)
                                                {
                                                    echo "<option selected hidden='' value='0'>Select an association...</option>";

                                                    for ($x = 0; $x < count($result); $x++)
                                                    {  
                                                        $row = $result[$x];       
                                                        echo "<option value=".htmlentities($row['association_id'], ENT_HTML5, 'UTF-8').">
                                                        
                                                        " . htmlentities($row['name'], ENT_HTML5, 'UTF-8') . "</option>"; 
                                                    }
                                                    echo "</select>";
                                                }
                                                else
                                                {
                                                    echo "<option selected disabled hidden='' value='0'>No associations found.</option>"; 
                                                    echo "</select>";   
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php 
                                    $tokenAddMembership = NoCSRF::generate('csrf_token_add_membership'); 
                                ?>
                                <input type="hidden" class="form-control" name="csrf_token_add_membership" id="csrf_token_add_membership" value="<?php echo $tokenAddMembership ?>"/>
                            </div>
                            <p>
                                Good to go...
                            </p>
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

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-header panel-init-open">
                                    <h3>
                                        <i class="fa fa-users">
                                        </i>
                                        <strong>
                                            Overview
                                        </strong>
                                    </h3>
                                </div>
                                <div class="panel-content">
                                    <p>
                                        Sort, filter and search basic member information.
                                    </p>
                                    <div class="row">
                                        <!-- MEMBERS TABLE -->
                                        <table class="col-sm-12 table table-hover table-dynamic filter-select">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        ID
                                                    </th>
                                                    <th>
                                                        Member Name
                                                    </th>
                                                    <th>
                                                        Committee
                                                    </th>
                                                    <th>
                                                        Association
                                                    </th>
                                                    <th>
                                                        Date Start (YYYY/MM/DD)
                                                    </th>
                                                    <th>
                                                        Date End (YYYY/MM/DD)
                                                    </th>
                                                    <th>
                                                        Comment
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <td>
                                                        ID
                                                    </td>
                                                    <td>
                                                        Member Name
                                                    </td>
                                                    <td>
                                                        Committee
                                                    </td>
                                                    <td>
                                                        Association
                                                    </td>
                                                    <td>
                                                        Date Start (YYYY/MM/DD)
                                                    </td>
                                                    <td>
                                                        Date End (YYYY/MM/DD)
                                                    </td>
                                                    <td>
                                                        Comment
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            <tbody>

                                                <?php

                                                // run query
                                                $result = $db->getAllMemberships();

                                                if(count($result) > 0)
                                                {
                                                    for ($x = 0; $x < count($result); $x++)
                                                    {  
                                                        $row = $result[$x];
                                                        
                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    ".htmlentities($row['membership_id'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['fname'], ENT_HTML5, 'UTF-8')." ".htmlentities($row['lname'], ENT_HTML5, 'UTF-8'). "
                                                                </td>
                                                                <td>";

                                                                    if(!empty($row['cname']))
                                                                    {
                                                                        echo htmlentities($row['cname'], ENT_HTML5, 'UTF-8');
                                                                    }
                                                                    else
                                                                    {
                                                                        echo "n/a";
                                                                    }  
                                                                    
                                                                    echo "
                                                                </td>
                                                                <td>";

                                                                    if(!empty($row['aname']))
                                                                    {
                                                                        echo htmlentities($row['aname'], ENT_HTML5, 'UTF-8');
                                                                    }
                                                                    else
                                                                    {
                                                                        echo "n/a";
                                                                    }  
                                                                    
                                                                    echo "
                                                                </td>
                                                                <td>";

                                                                    if(!empty($row['date_starts']))
                                                                    {
                                                                        echo htmlentities($row['date_starts'], ENT_HTML5, 'UTF-8');
                                                                    }
                                                                    else
                                                                    {
                                                                        echo htmlentities($row['date_started'], ENT_HTML5, 'UTF-8');
                                                                    }  
                                                                    
                                                                    echo "
                                                                </td>
                                                                <td>";

                                                                    if(!empty($row['date_ends']))
                                                                    {
                                                                        echo htmlentities($row['date_ends'], ENT_HTML5, 'UTF-8');
                                                                    }
                                                                    else
                                                                    {
                                                                        echo htmlentities($row['date_ended'], ENT_HTML5, 'UTF-8');
                                                                    }  
                                                                    
                                                                    echo "
                                                                </td>
                                                                <td>";

                                                                    if(!empty($row['successorReason']))
                                                                    {
                                                                        echo htmlentities($row['successorReason'], ENT_HTML5, 'UTF-8');
                                                                    }
                                                                    else
                                                                    {
                                                                        echo "n/a";
                                                                    }  
                                                                    
                                                                    echo "
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
                                                                No memberships found.
                                                            </td>
                                                            <td>
                                                            </td>
                                                            <td>
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

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4>
                                            Delete Single Membership
                                        </h4>
                                    </div>
                                    <div class="panel-collapse">
                                        <div class="panel-body">
                                            <form enctype="multipart/form-data" class="form-horizontal form-validation" method="POST" action="assets/deleteMembershipSingle.php" role="form">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>
                                                            Search for Single Membership:
                                                        </label>
                                                        <select name="membershipDeleteSingle" class="form-control" data-placeholder="Select one  membership..." data-search="true" required="">
                                                            <?php 

                                                            // run query
                                                            $result = $db->getAllMemberships();

                                                            if(count($result) > 0)
                                                            {
                                                                echo "<option selected hidden='' value=''>Select one membership...</option>";

                                                                for ($x = 0; $x < count($result); $x++)
                                                                {  
                                                                    $row = $result[$x];       
                                                                    echo "<option value=".htmlentities($row['membership_id'], ENT_HTML5, 'UTF-8').">Membership ID:" . htmlentities($row['membership_id']) . "</option>"; 
                                                                }
                                                                echo "</select>";
                                                            }
                                                            else
                                                            {
                                                                echo "<option selected disabled hidden='' value=''>No memberships found.</option>"; 
                                                                echo "</select>";   
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php 
                                                            $tokenDeleteMembershipSingle = NoCSRF::generate('csrf_token_delete_membership_single'); 
                                                        ?>
                                                        <input type="hidden" class="form-control" name="csrf_token_delete_membership_single" id="csrf_token_delete_membership_single" value="<?php echo $tokenDeleteMembershipSingle ?>"/>
                                                    </div>
                                                    <div class="text-right">
                                                        <button class="btn btn-embossed btn-danger" type="submit">
                                                            Delete Membership
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4>
                                            Delete Multiple Memberships
                                        </h4>
                                    </div>
                                    <div class="panel-collapse">
                                        <div class="panel-body">
                                            <form enctype="multipart/form-data" class="form-horizontal form-validation" method="POST" action="assets/deleteMembershipMultiple.php" role="form">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>
                                                            Search for Multiple Memberships:
                                                        </label>
                                                        <select name="membershipDeleteMultiple[]" class="form-control" data-placeholder="Select one or various membership(s)..." multiple="" required="">
                                                            <?php 

                                                            // run query
                                                            $result = $db->getAllMemberships();

                                                            if(count($result) > 0)
                                                            {
                                                                echo "<option hidden='' value=''>Select one membership...</option>";

                                                                for ($x = 0; $x < count($result); $x++)
                                                                {  
                                                                    $row = $result[$x];       
                                                                    echo "<option value=".htmlentities($row['membership_id'], ENT_HTML5, 'UTF-8').">Membership ID:" . htmlentities($row['membership_id']) . "</option>"; 
                                                                }
                                                                echo "</select>";
                                                            }
                                                            else
                                                            {
                                                                echo "<option selected disabled hidden='' value=''>No memberships found.</option>"; 
                                                                echo "</select>";   
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php 
                                                            $tokenDeleteMembershipMultiple = NoCSRF::generate('csrf_token_delete_membership_multiple'); 
                                                        ?>
                                                        <input type="hidden" class="form-control" name="csrf_token_delete_membership_multiple" id="csrf_token_delete_membership_multiple" value="<?php echo $tokenDeleteMembershipMultiple ?>"/>
                                                    </div>
                                                    <div class="text-right">
                                                        <button class="btn btn-embossed btn-danger" type="submit">
                                                            Delete Membership(s)
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
<!-- Show Dropdown on Mouseover -->
<script src="../assets/plugins/bootstrap-dropdown/bootstrap-hover-dropdown.min.js">
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

    var customPeriodFields = document.getElementById('customPeriodFields'),
        inputsPeriods = document.getElementById('inputsPeriods'),
        checkBox = document.getElementById('checkboxCustomPeriod');
    checkBox.checked = false;
    checkBox.onchange = function() {
        customPeriodFields.style.display = this.checked ? 'block' : 'none';
        inputsPeriods.style.display = this.checked ? 'none' : 'block';
    };

    checkBox.onchange();
</script>