<?php

// Error Reporting komplett abschalten
error_reporting(0);

session_start();

if (!isset($_SESSION['user_login_string']) || !isset($_SESSION['user_role']) || !isset($_SESSION['user_username'])) {
    $_SESSION['notify_error_user'] = "Don't know what's happening... Maybe login first.";

    // redirect to login page
    header('Status: 401'); // Status unauthorized
    header('Location: ../'.urlencode('index.php'));
    exit;
} elseif (isset($_SESSION['user_login_string']) && isset($_SESSION['user_role'])  && isset($_SESSION['user_username'])) {
    if ($_SESSION['user_role'] === "Admin") {

        // redirect to search interface
        header('Status: 200'); // Status ok
        header('Location: ../admin/'.urlencode('index.php'));
        exit;
    } elseif ($_SESSION['user_role'] !== "Employee") {
        $_SESSION['notify_error_user'] = "Don't know what's happening...";

        // redirect to login page
        header('Status: 401'); // Status unauthorized
        header('Location: ../'.urlencode('index.php'));
        exit;
    }
}

include_once('./db/DBHandler_ro.php');
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
            MAS 1.0 | Search Interface
        </title>
        <!-- CSS STYLES -->
        <link href="../assets/css/bundle.css" rel="stylesheet"/>
        <!-- MATERIAL DESIGN STYLES -->
        <link href="../assets/md-layout/material-design/css/material.css" rel="stylesheet"/>
        <link href="../assets/md-layout/css/layout.css" rel="stylesheet"/>
        <!-- PAGE STYLES -->
        <link href="../assets/plugins/datatables/dataTables.min.css" rel="stylesheet"/>
    </head>
    <!-- BEGIN BODY -->
    <body class="fixed-topbar fixed-sidebar sidebar-collapsed theme-sltl color-green">
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
                        <li class=" nav-active active">
                            <a href="index.php">
                                <i class="fa fa-home">
                                </i>
                                <span>
                                    Home
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
                    <div class="row">
                        <div class="col-sm-3">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="panel no-bd bd-3 panel-stat">
                                <div class="panel-body p-15 p-b-0">
                                  <div class="row m-b-10">
                                    <div class="col-xs-3 big-icon">
                                      <i class="fa fa-exchange"></i>
                                    </div>
                                    <div class="col-xs-9">
                                            <h1 class="f-40 m-0 w-300 text-primary">
                                            <?php

                                                // run query
                                                $result = $db->getNrOfMemberships();

                                                if ($result != null) {
                                                    echo $result;
                                                } else {
                                                    echo "0";
                                                }
                                            ?>
                                            </h1>
                                            <p>
                                                Memberships
                                            </p>
                                        <small class="stat-title">as of:<br/> <?php $t=time(); echo(date("Y-m-d h:i:sa", $t)); ?> </small>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="panel no-bd bd-3 panel-stat">
                                <div class="panel-body p-15 p-b-0">
                                  <div class="row m-b-10">
                                    <div class="col-xs-3 big-icon">
                                      <i class="fa fa-users"></i>
                                    </div>
                                    <div class="col-xs-9">
                                            <h1 class="f-40 m-0 w-300 text-primary">
                                            <?php

                                                // run query
                                                $result = $db->getNrOfMembers();

                                                if ($result != null) {
                                                    echo $result;
                                                } else {
                                                    echo "0";
                                                }
                                            ?>
                                            </h1>
                                            <p>
                                                Members
                                            </p>
                                        <small class="stat-title">as of:<br/> <?php $t=time(); echo(date("Y-m-d h:i:sa", $t)); ?> </small>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="panel no-bd bd-3 panel-stat">
                                <div class="panel-body p-15 p-b-0">
                                  <div class="row m-b-10">
                                    <div class="col-xs-3 big-icon">
                                      <i class="fa fa-institution"></i>
                                    </div>
                                    <div class="col-xs-9">
                                            <h1 class="f-40 m-0 w-300 text-primary">
                                            <?php

                                                // run query
                                                $result = $db->getNrOfCommittees();

                                                if ($result != null) {
                                                    echo $result;
                                                } else {
                                                    echo "0";
                                                }
                                            ?>
                                            </h1>
                                            <p>
                                                Committees
                                            </p>
                                        <small class="stat-title">as of:<br/> <?php $t=time(); echo(date("Y-m-d h:i:sa", $t)); ?> </small>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="panel no-bd bd-3 panel-stat">
                                <div class="panel-body p-15 p-b-0">
                                  <div class="row m-b-10">
                                    <div class="col-xs-3 big-icon">
                                      <i class="fa fa-graduation-cap"></i>
                                    </div>
                                    <div class="col-xs-9">
                                            <h1 class="f-40 m-0 w-300 text-primary">
                                            <?php

                                                // run query
                                                $result = $db->getNrOfAssociations();

                                                if ($result != null) {
                                                    echo $result;
                                                } else {
                                                    echo "0";
                                                }
                                            ?>
                                            </h1>
                                            <p>
                                                Associations
                                            </p>
                                        <small class="stat-title">as of:<br/> <?php $t=time(); echo(date("Y-m-d h:i:sa", $t)); ?> </small>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        </div>
                    </div>
                    <div class="header text-center">
                        <h2><strong>Memberships</strong></h2>
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
                                        Sort, filter and search all memberships.
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

                                                if (count($result) > 0) {
                                                    for ($x = 0; $x < count($result); $x++) {
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

                                                        if (!empty($row['cname'])) {
                                                            echo htmlentities($row['cname'], ENT_HTML5, 'UTF-8');
                                                        } else {
                                                            echo "n/a";
                                                        }

                                                        echo "
                                                                </td>
                                                                <td>";

                                                        if (!empty($row['aname'])) {
                                                            echo htmlentities($row['aname'], ENT_HTML5, 'UTF-8');
                                                        } else {
                                                            echo "n/a";
                                                        }

                                                        echo "
                                                                </td>
                                                                <td>";

                                                        if (!empty($row['date_starts'])) {
                                                            echo htmlentities($row['date_starts'], ENT_HTML5, 'UTF-8');
                                                        } else {
                                                            echo htmlentities($row['date_started'], ENT_HTML5, 'UTF-8');
                                                        }

                                                        echo "
                                                                </td>
                                                                <td>";

                                                        if (!empty($row['date_ends'])) {
                                                            echo htmlentities($row['date_ends'], ENT_HTML5, 'UTF-8');
                                                        } else {
                                                            echo htmlentities($row['date_ended'], ENT_HTML5, 'UTF-8');
                                                        }

                                                        echo "
                                                                </td>
                                                                <td>";

                                                        if (!empty($row['successorReason'])) {
                                                            echo htmlentities($row['successorReason'], ENT_HTML5, 'UTF-8');
                                                        } else {
                                                            echo "n/a";
                                                        }

                                                        echo "
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }
                                                } else {
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
                    <div class="header text-center">
                        <h2><strong>Members</strong></h2>
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
                                                        First Name
                                                    </th>
                                                    <th>
                                                        Last Name
                                                    </th>
                                                    <th>
                                                        Student Nr
                                                    </th>
                                                    <th>
                                                        Email
                                                    </th>
                                                    <th>
                                                        Timestamp Added
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <td>
                                                        First Name
                                                    </td>
                                                    <td>
                                                        Last Name
                                                    </td>
                                                    <td>
                                                        Student Nr
                                                    </td>
                                                    <td>
                                                        Email
                                                    </td>
                                                    <td>
                                                        Timestamp Added
                                                    </td>
                                                </tr>
                                            </tfoot>
                                            <tbody>

                                                <?php

                                                // run query
                                                $result = $db->getAllMembersPersonal();

                                                if (count($result) > 0) {
                                                    for ($x = 0; $x < count($result); $x++) {
                                                        $row = $result[$x];

                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    ".htmlentities($row['fname'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['lname'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['student_nr'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['email'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                                <td>
                                                                    ".htmlentities($row['timestamp_added'], ENT_HTML5, 'UTF-8')."
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }
                                                } else {
                                                    echo "
                                                        <tr>
                                                            <td>
                                                                No members found.
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

                </div>
                <div class="footer m-t-100 p-b-20">
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
<script src="../assets/plugins/jquery-cookies/jquery.cookies.min.js">
</script>
<!-- MATERIAL DESIGN PLUGINS -->
<script src="../assets/md-layout/material-design/js/material.js">
</script>
<!-- HELPER PLUGINS -->
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
