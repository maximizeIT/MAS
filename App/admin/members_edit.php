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
            MAS 1.0 | Member Administration - Edit Member(s)
        </title>
        <!-- CSS STYLES -->
        <link href="../assets/css/bundle.css" rel="stylesheet"/>
        
        <!-- MATERIAL DESIGN STYLES -->
        <link href="../assets/md-layout/material-design/css/material.css" rel="stylesheet"/>
        <link href="../assets/md-layout/css/layout.css" rel="stylesheet"/>
        <script>
            function getMemberForEdit() {

                var e = document.getElementById("editSelectMember");
                var memberId = e.options[e.selectedIndex].value;

                // Empty & disable inputs
                document.getElementById('member_id').value = '';
                document.getElementById('fname').value = '';
                document.getElementById('lname').value = '';
                document.getElementById('email').value = '';
                document.getElementById('student_nr').value = '';

                document.getElementById('fname').disabled = true;
                document.getElementById('lname').disabled = true;
                document.getElementById('email').disabled = true;
                document.getElementById('student_nr').disabled = true;
                document.getElementById('submitUpdateBtn').disabled = true;

                // Store originals to check for changes later
                document.getElementById('fname_org').value = '';
                document.getElementById('lname_org').value = '';
                document.getElementById('email_org').value = '';
                document.getElementById('student_nr_org').value = '';

                if (memberId == 0) {
                    document.getElementById("editRetrievedMember").innerHTML = "<p>Select a member from the dropdown list above...</p>";

                    return;
                }
                else 
                {
                    var xmlhttp = new XMLHttpRequest();
                    
                    xmlhttp.onreadystatechange = function() 
                    {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                        {
                            console.log('raw response: ', xmlhttp.responseText);

                            var arr = JSON.parse(xmlhttp.responseText);
                        
                            console.log('parsed response: ', arr);

                            if(arr !== null)
                            {
                                // Enable & fill inputs
                                document.getElementById('fname').disabled = false;
                                document.getElementById('lname').disabled = false;
                                document.getElementById('email').disabled = false;
                                document.getElementById('student_nr').disabled = false;
                                document.getElementById('submitUpdateBtn').disabled = false;

                                document.getElementById('member_id').value = arr.member_id;
                                document.getElementById('fname').value = arr.fname;
                                document.getElementById('lname').value = arr.lname;
                                document.getElementById('email').value = arr.email;
                                document.getElementById('student_nr').value = arr.student_nr;

                                // Store originals to check for changes later
                                document.getElementById('fname_org').value = arr.fname;
                                document.getElementById('lname_org').value = arr.lname;
                                document.getElementById('email_org').value = arr.email;
                                document.getElementById('student_nr_org').value = arr.student_nr;

                                document.getElementById("editRetrievedMember").innerHTML = "";
                            }
                            else {
                                document.getElementById("editRetrievedMember").innerHTML = "<p class='label label-danger'>Something went wrong... Please contact system admin.</p>";
                            }  
                        }
                    };

                    xmlhttp.open("GET", "assets/editMember.php?memberId=" + memberId, true);
                    xmlhttp.send();
                }
            }
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
                                <li>
                                    <a href="members_add.php">
                                        Add Member(s)
                                    </a>
                                </li>
                                <li class="active">
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
                                        <li>
                                            <a href="members_add.php">
                                                Add Member(s)
                                            </a>
                                        </li>
                                        <li class="active">
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
                <div class="page-content">
                    <div class="header text-center">
                        <h2>
                            <strong>
                                Edit Member(s)
                            </strong>
                        </h2>
                    </div>
                    <div class="row p-20 text-center">
                        <div class="form-group">
                            <select class="form-control input-lg" data-placeholder="Select a member..." required="" id="editSelectMember" name="editSelectMember" onchange="getMemberForEdit()">
                                <?php
                                    // run query
                                    $result = $db->getAllMembersPersonal();

                                    if(count($result) > 0)
                                    {
                                        echo "<option selected hidden='' value='0'>Select a member...</option>";

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
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel">
                                <div class="panel-header">
                                    <h3>
                                        <i class="icon-magnifier">
                                        </i>
                                        <strong>
                                            Edit Member
                                        </strong>
                                    </h3>
                                </div>
                                <div class="panel-content">
                                    <div class="row">
                                        <div class='col-sm-12'>
                                            <div id="editRetrievedMember">
                                                <p>Select a member from the dropdown list above...</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <?php

                                        if (isset($_SESSION['notify_success_update_member'])) {
                                            echo '
                                            <div class="col-md-12">
                                              <div class="md-alert alert alert-success alert-dismissible fade in" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <div class="media-body width-100p">
                                                  <p class="md-title">Success</p>
                                                  <div class="md-txt">
                                                    <p>' . htmlentities($_SESSION['notify_success_update_member'], ENT_HTML5, 'UTF-8') . '</p>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                                            unset($_SESSION['notify_success_update_member']);
                                        }
                                        elseif (isset($_SESSION['notify_error_update_member'])) {
                                            echo '
                                            <div class="col-md-12">
                                              <div class="md-alert alert alert-danger alert-dismissible fade in" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <div class="media-body width-100p">
                                                  <p class="md-title">Error</p>
                                                  <div class="md-txt">
                                                    <p>' . htmlentities($_SESSION['notify_error_update_member'], ENT_HTML5, 'UTF-8') . '</p>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                                            unset($_SESSION['notify_error_update_member']);
                                        }
                                        ?>
                                        <div class='col-sm-12'>
                                            <p class='label bg-primary'>* required fields.</p>
                                            <form enctype='multipart/form-data' class='form-validation form-horizontal' method='POST' action="assets/updateMemberSingle.php">
                                                <div class='form-group'>
                                                    <label for='member_id' class='col-sm-2 control-label'>Internal Member ID</label>
                                                    <div class='col-sm-8'>
                                                        <input autocomplete='off' placeholder='Internal member id' class='form-control' id='member_id' name='member_id' type='text' readonly>
                                                        </input>
                                                    </div>
                                                </div>
                                                <div class='form-group'>
                                                    <label for='fname' class='col-sm-2 control-label'>First Name *</label>
                                                    <div class='col-sm-8 prepend-icon'>
                                                        <input autocomplete='off' class='form-control' id='fname' maxlength='15' minlength='3' name='fname' placeholder='First name' type='text' required  autocomplete='off' disabled>
                                                                <i class='fa fa-user'>
                                                                </i>
                                                        </input>
                                                    </div>
                                                </div>
                                                <div class='form-group'>
                                                    <label for='lname' class='col-sm-2 control-label'>Last Name *</label>
                                                    <div class='col-sm-8 prepend-icon'>
                                                        <input autocomplete='off' class='form-control' id='lname' maxlength='25' minlength='3' name='lname' placeholder='Last name' type='text' required autocomplete='off' disabled>
                                                                <i class='fa fa-user'>
                                                                </i>
                                                        </input>
                                                    </div>
                                                </div>
                                                <div class='form-group'>
                                                    <label for='email' class='col-sm-2 control-label'>Email *</label>
                                                    <div class='col-sm-8 prepend-icon'>
                                                        <input type='email' class='form-control' id='email' name='email' required placeholder='Email' autocomplete='off' disabled>
                                                                <i class='fa fa-envelope'>
                                                                </i>
                                                        </input>
                                                    </div>
                                                </div>
                                                <div class='form-group'>
                                                    <label for='student_nr' class='col-sm-2 control-label'>Student ID</label>
                                                    <div class='col-sm-8'>
                                                        <input type='text' data-btn-before='primary' data-step='1'  data-btn-after='primary' name='student_nr' id='student_nr' class='numeric-stepper form-control' data-max='999999' maxlength='6' autocomplete='off' disabled>
                                                        </input>
                                                    </div>
                                                <div class="form-group">
                                                    <?php 
                                                        $tokenUpdateMember = NoCSRF::generate('csrf_token_update_member'); 
                                                    ?>
                                                    <input type="hidden" class="form-control" name="csrf_token_update_member" id="csrf_token_update_member" value="<?php echo $tokenUpdateMember ?>"/>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control" name="fname_org" id="fname_org" readonly/>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control" name="lname_org" id="lname_org" readonly/>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control" name="email_org" id="email_org" readonly/>
                                                </div>
                                                <div class="form-group">
                                                    <input type="hidden" class="form-control" name="student_nr_org" id="student_nr_org" readonly/>
                                                </div>
                                                <div class='form-group'>
                                                    <div class='col-sm-offset-2 col-sm-8'>
                                                      <button type='submit' id="submitUpdateBtn" class='btn btn-embossed btn-primary' disabled>Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>                
                                    </div>
                                </div>
                            </div>

                        </div>
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
            </div>
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