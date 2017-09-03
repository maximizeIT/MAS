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
            MAS 1.0 | Member Administration - Add Member(s)
        </title>
        <!-- CSS STYLES -->
        <link href="../assets/css/bundle.css" rel="stylesheet"/>
        
        <!-- MATERIAL DESIGN STYLES -->
        <link href="../assets/md-layout/material-design/css/material.css" rel="stylesheet"/>
        <link href="../assets/md-layout/css/layout.css" rel="stylesheet"/>
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
                        <li class="nav-parent nav-active active">
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
                                <li class="active">
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
                            <ul class="nav nav-horizontal mmenu">
                                <!-- standard drop down -->
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-close-others="true" data-delay="100" data-hover="dropdown" data-toggle="dropdown" href="#">
                                        Member Management
                                        <b class="caret">
                                        </b>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="index.php">
                                                Members Overview
                                            </a>
                                        </li>
                                        <li class="active">
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
                                    <!-- end dropdown-menu -->
                                </li>
                            </ul>
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
                        <h2>
                            <strong>
                                Add Member(s)
                            </strong>
                        </h2>
                    </div>

                    <div class="row">
                        <?php
                        if (isset($_SESSION['notify_success_add_members'])) {
                            echo '
                            <div class="col-md-12">
                              <div class="md-alert alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                <div class="media-body width-100p">
                                  <p class="md-title">Success</p>
                                  <div class="md-txt">
                                    <p>' . htmlentities($_SESSION['notify_success_add_members'], ENT_HTML5, 'UTF-8') . '</p>
                                  </div>
                                </div>
                              </div>
                            </div>';
                            unset($_SESSION['notify_success_add_members']);
                        }
                        elseif (isset($_SESSION['notify_error_add_members'])) {
                            echo '
                            <div class="col-md-12">
                              <div class="md-alert alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                <div class="media-body width-100p">
                                  <p class="md-title">Error</p>
                                  <div class="md-txt">
                                    <p>' . htmlentities($_SESSION['notify_error_add_members'], ENT_HTML5, 'UTF-8') . '</p>
                                  </div>
                                </div>
                              </div>
                            </div>';
                            unset($_SESSION['notify_error_add_members']);
                        }
                        ?>
                        <div class="col-md-2 col-md-offset-5">
                            <!-- Add Members -->
                            <form id='member_add' class="text-center" action='' method='POST' accept-charset='UTF-8'>

                                <input type="text" name="member_add_nr" autocomplete="off" value="1" style="text-align: center; width: 100%;"><br>
                                <br/>
                                <button class="btn btn-embossed btn-primary" type="submit">
                                    Add Member(s)
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php
                        if($_SERVER['REQUEST_METHOD'] == 'POST') 
                        {
                            if(isset($_POST['member_add_nr']))
                            {
                                $count = $_POST['member_add_nr'];
                            }

                            if(isset($count))
                            {
                                echo "
                                    <div class='row p-20'>
                                        <p class='label bg-primary'>* required fields.</p>
                                        <form id='memberFormAdd' class='form-horizontal form-validation' method='POST' action=''>";

                                            for ($i=0; $i < $count; $i++) 
                                            { 
                                                echo "
                                                    <div class='col-md-12'>
                                                        <h2>Add Member ".($count == 1 ? '' : '#'.($i+1))."<small></small>
                                                        </h2>
                                                        <div class='col-md-12'>
                                                            <div class='col-sm-3 p-20'>
                                                                <div class='form-group'>
                                                                    <label for='fname_".($i + 1)."'>
                                                                        First Name *
                                                                    </label>
                                                                    <input autocomplete='off' required class='form-control' id='fname_".($i + 1)."' maxlength='15' minlength='3' name='member_fname_".($i + 1)."' placeholder='Enter first name' type='text'>
                                                                    </input>
                                                                </div>
                                                            </div>
                                                            <div class='col-sm-3 p-20'>
                                                                <div class='form-group'>
                                                                    <label for='lname_".($i + 1)."'>
                                                                        Last Name *
                                                                    </label>
                                                                    <input autocomplete='off' required class='form-control' id='lname_".($i + 1)."' maxlength='25' minlength='3' name='member_lname_".($i + 1)."' placeholder='Enter last name' type='lname'>
                                                                    </input>
                                                                </div>
                                                            </div>
                                                            <div class='col-sm-3 p-20'>
                                                                <div class='form-group'>
                                                                    <label class='form-label' for='email_".($i + 1)."'>
                                                                        Email *
                                                                    </label>
                                                                    <input autocomplete='off' required class='form-control' id='email_".($i + 1)."' name='member_email_".($i + 1)."' placeholder='Enter email' type='email'>
                                                                    </input>
                                                                </div>
                                                            </div>
                                                            <div class='col-sm-3 p-20'>
                                                                <div class='form-group'>
                                                                    <label class='form-label' for='studentnr_".($i + 1)."'>
                                                                        Student ID
                                                                    </label>
                                                                    <div>
                                                                        <input type='text' value='000000' data-btn-before='primary' data-step='1'  data-btn-after='primary' name='member_studentnr_".($i + 1)."' class='numeric-stepper form-control' data-max='999999' maxlength='6' />
                                                                        </input>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ";
                                            }
                                            echo "
                                                    <div class='col-sm-12 text-center'>
                                                        <button type='reset' class='btn btn-embossed btn-default'>Reset ALL Fields</button>
                                                        <button type='submit' class='btn btn-embossed btn-primary'>Save</button>
                                                    </div>
                                                    <input type='hidden' name='memberAddCount' id='memberAddCount' value='".$count."'/>
                                                </div>
                                        </form>
                                    </div>
                                "; 
                            }

                            if(isset($_POST['memberAddCount']))
                            {
                                $count = $_POST['memberAddCount'];
                                
                                $db->start_transaction();

                                for ($i=0; $i < $count; $i++) 
                                {
                                    $fname = $_POST['member_fname_'.($i+1)];
                                    $lname = $_POST['member_lname_'.($i+1)];
                                    $email = $_POST['member_email_'.($i+1)];
                                    $studentId = $_POST['member_studentnr_'.($i+1)];

                                    $result = $db->addMemberSingle( $fname , $lname , $email , $studentId );

                                    if($result)
                                    {
                                        $db->commit();
                                        $_SESSION['notify_success_add_members'] = 'Member(s) successfully added!';
                                    }
                                    else
                                    {
                                        $db->rollback();

                                        $_SESSION['notify_error_add_members'] = 'Something went wrong. Try again or contact admin.';
                                    }
                                }
                            }
                        }
                    ?>
                </div>
                <div class="footer m-t-100">
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
        </section>
        <!-- END MAIN CONTENT -->
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
<!-- A mobile and touch friendly input spinner component for Bootstrap -->
<script src="../assets/plugins/touchspin/jquery.bootstrap-touchspin.min.js">
</script>
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
</script>