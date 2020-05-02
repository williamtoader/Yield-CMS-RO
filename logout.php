<?php
    session_name("Private");
    session_start();
    session_destroy();
    header("Location: login.php");
