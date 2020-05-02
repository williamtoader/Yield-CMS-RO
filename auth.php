<?php
require "dataStore.php";
require_once "config.php";

function isSecure() {
    global $_SERVER;
    return
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $_SERVER['SERVER_PORT'] == 443;
}

session_set_cookie_params(0,"/", $websiteDomain, isSecure(), true);
session_name("Private");
session_start();
session_regenerate_id();
if(isset($_POST["user"]) && isset($_POST["pass"])) {
    if(checkPassword($_POST["user"], $_POST["pass"])) {
        echo "SUCCESS";
        if(!isset($_SESSION["user"])) $_SESSION["user"] = $_POST["user"];
    }
    else header("HTTP/1.1 403 LOGIN_ERR");
}
else header("HTTP/1.1 403 LOGIN_ERR");
echo "done";
session_write_close();
