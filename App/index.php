<?php

// Error Reporting komplett abschalten
error_reporting(0);

session_start();

if ( isset($_SESSION['user_login_string']) && isset($_SESSION['user_role'])  && isset($_SESSION['user_username']) ) 
{
    if ($_SESSION['user_role'] === "Admin") {
        
        // redirect to search interface
        header('Status: 200'); // Status ok
        header('Location: ../admin/'.urlencode('index.php'));
        exit;
    }
    elseif ($_SESSION['user_role'] === "Employee") {
        $_SESSION['notify_error_user'] = "Don't know what's happening...";

        // redirect to login page
        header('Status: 401'); // Status unauthorized
        header('Location: ../search/'.urlencode('index.php'));
        exit;
    }
}

// Prevention CSRF attacks
include_once('assets/includes/nocsrf.php');

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0" name="viewport"/>
        <meta content="Membership Administration Software (MAS) 1.0" name="description"/>
        <meta content="Max Scholz" name="author"/>
        <title>
            MAS 1.0 | Log In
        </title>
        <!-- CSS STYLES -->
        <link href="./assets/css/bundle.css" rel="stylesheet"/>
        <!-- MATERIAL DESIGN STYLES -->
        <link href="./assets/md-layout/material-design/css/material.css" rel="stylesheet"/>
        <link href="./assets/md-layout/css/layout.css" rel="stylesheet"/>
        </script>
    </head>
    <body class="account2 color-green" data-page="login">
        <!-- BEGIN LOGIN BOX -->
        <div class="container" id="login-block">
            <i class="user-img icons-faces-users-03">
            </i>
            <div class="col-xs-12 bg-white p-10">
                <h3 class="text-center">
                    <strong>
                        Membership Administration System (MAS) 1.0
                    </strong>
                </h3>

            <div class="col-xs-12 account-form">
                <form enctype="multipart/form-data" class="form-validation" role="form" method="POST" action="assets/scripts/login.php" autocomplete="off">
                    <div class="form-group">
                        <div class="prepend-icon">
                            <input autocomplete="off" autofocus class="form-control form-white username" minlength="3" id="username" name="username" placeholder="Enter your username..." required="" type="text">
                                <i class="icon-user">
                                </i>
                            </input>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="prepend-icon">
                            <input autocomplete="off" class="form-control form-white password" minlength="4" id="password" name="password" placeholder="Enter your password..." required="" type="password">
                                <i class="icon-lock">
                                </i>
                            </input>
                        </div>
                    </div>
                    <?php
                        if ( isset($_SESSION['notify_error_user']) ) {
                    ?>
                    <div class="form-group">
                        <div class="alert alert-danger">
                            <span class="glyphicon glyphicon-info-sign"></span> <?php echo htmlentities($_SESSION['notify_error_user'], ENT_HTML5, 'UTF-8') ?>
                        </div>
                    </div>
                    <?php
                        }
                        unset($_SESSION["notify_error_user"]);
                    ?>
                    <div class="form-group">
                        <?php 
                            $tokenUserLogin = NoCSRF::generate('csrf_token_user_login'); 
                        ?>
                        <input type="hidden" class="form-control" name="csrf_token_user_login" id="csrf_token_user_login" value="<?php echo $tokenUserLogin ?>"/>
                    </div>
                    <div>
                        <p><span class="label f-12">Admin</span><br/>Username: m.scholz<br/>Password: abc123</p>
                        <p><span class="label f-12">Employee</span><br/>Username: h.peter<br/>Password: abc123</p>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-lg btn-embossed btn-primary text-center" data-style="expand-left" name="submit-login" type="submit">
                            Log In
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- END LOGIN BOX -->
        <p class="account-copyright">
            <span>
                Copyright
                <span class="copyright">
                    ©
                </span>
                2016
            </span>
            <span>
                Max Scholz
            </span>
            .
            <span>
                All rights reserved.
            </span>
            <span>
                <a data-target="#about" data-toggle="modal">
                    About
                </a>
                | MAS v1.0
            </span>
        </p>
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
    </body>
</html>
<!-- JQUERY -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- BOOTSTRAP -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>