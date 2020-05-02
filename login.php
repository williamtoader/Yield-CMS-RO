<?php
session_name("Private");
session_start();
if (isset($_SESSION["user"])) {
    header("Location: cms.php");
    die();
}
?>
<!DOCTYPE html>
<html style="height: 100%;">
    <head>
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans|Montserrat&display=swap" rel="stylesheet">
        <link rel = "stylesheet" href = "assets/bootstrap/bootstrap.min.css"/>
        <link rel = "stylesheet" href = "assets/bootstrap/bootstrap-grid.min.css"/>
        <style>
            body {
                background-color: #555555;
            }
            .center_block {
                margin: 0;
                position: absolute;
                top: 50%;
                left: 50%;
                -ms-transform: translate(-50%, -50%);
                transform: translate(-50%, -50%);
            }

            .form_container {
                border-radius: 0px;
                padding: 50px;
            }
        </style>
    </head>

    <body style="height: 100%">
        <div class="container-fluid" style="height: 100%;width: 100%;">
            <div class="container bg-white text-black center_block form_container" style="width: 400px; ">
                <!-- Login form -->
                <h2 style="margin-bottom: 20px;">Login</h2>
                <div>
                    <input type="text" id="username" class="form-control" placeholder="Username"><br>
                    <input type="password" id="password" class="form-control" placeholder="Password"><br>
                    <input type="button" id="btnSubmit" class="form-control btn btn-outline-dark" value="Log In" onclick="validate()">
                    <br>
                    <div class="text-danger" style="margin-top: 20px;" id="display"></div>
                </div>
            </div>
        </div>
        <script src = "assets/jquery-3.3.1.slim.min.js"></script>
        <script src = "assets/popper.min.js"></script>
        <script src = "assets/bootstrap/bootstrap.min.js"></script>
        <script src = "assets/md5.min.js"></script>
        <script src = "auth.js"></script>
    </body>
</html>
