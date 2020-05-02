<?php
require "pluginManager.php";
loadPluginFrom("plugins/endpoint_tester");
loadPluginFrom("plugins/gdrive_generator");
session_name("Private");
session_start();
if ((!isset($_SESSION["user"])) || $_SESSION["user"] === NULL) {
    die();
}

if(isset($_POST["type"]) && isset($_POST["plugin"]) && isset($_POST["action"])) {
    global $currentWorkerTimeout, $defaultWorkerTimeout;
    $output = "";
    if($_POST["type"] == "basic") {
        if(isset($_POST["data"]))$output = verifiedExecution($_SESSION["user"],$_POST["plugin"],$_POST["action"],"BASIC_PUT\n{$_POST["data"]}");
        else $output = verifiedExecution($_SESSION["user"],$_POST["plugin"],$_POST["action"],"BASIC");
    }
    else if($_POST["type"] == "file_upload" && isset($_FILES["file"])) {
        $currentWorkerTimeout = 30;
        $fileData = "{\"filename\":\"{$_FILES["file"]["name"]}\", \"tmp\":\"{$_FILES["file"]["tmp_name"]}\"}";
        if(isset($_POST["data"]))$output = verifiedExecution($_SESSION["user"],$_POST["plugin"],$_POST["action"],"FILE_PUT\n$fileData\n{$_POST["data"]}");
        else $output = verifiedExecution($_SESSION["user"],$_POST["plugin"],$_POST["action"],"FILE\n$fileData");
        unlink($_FILES["file"]["tmp_name"]);
        $currentWorkerTimeout = $defaultWorkerTimeout;
    }

    echo $output;
}