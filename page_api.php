<?php
require "dataStore.php";
require "ncld_plugin_manager/pluginManager.php";
global $loadedPlugins;
//Enable gdrive integration
loadPluginFrom("ncld_plugin_manager/plugins/gdrive_generator");

session_name("Private");
session_start();

//Create DB Document entry
function createPageEntryDB($name, $data)
{
    $autoId = setPage(null, $name, null, json_decode($data, true));
    $link = "page.php?id=" . $autoId;
    setPage($autoId, null, $link, null);
    return $autoId;
}

function updatePageEntryDB($name, $data, $id)
{
    setPage($id, $name, null, json_decode($data, true));
}

function updateDocument($id, $newEntryName) {
    global $currentWorkerTimeout, $defaultWorkerTimeout, $loadedPlugins;
    if (file_exists($_FILES['file']['tmp_name']) && isset($loadedPlugins["gdrive"])) {

        /* Getting file name */
        $currentWorkerTimeout = 30;
        $fileData = "{\"filename\":\"{$_FILES["file"]["name"]}\", \"tmp\":\"{$_FILES["file"]["tmp_name"]}\"}";
        $output = verifiedExecution($_SESSION["user"], "gdrive", "upload", "FILE\n$fileData");
        unlink($_FILES["file"]["tmp_name"]);
        $currentWorkerTimeout = $defaultWorkerTimeout;


        //ADD PAGE TO DB
        updatePageEntryDB($newEntryName, '{"type":"document","gdriveID":"' . $output . '"}', $id);
        echo "{\"id\":{$id}}";

        //GENERATE STATIC PAGE
        $myfile = fopen("static/file_" . $id . ".html", "w") or die("Unable to open file!");
        $gdrive_file_id = $output;
        fwrite($myfile, "<iframe src='https://drive.google.com/file/d/{$gdrive_file_id}/preview' width='100%' height='100%' style='border:0 !important;'></iframe>");
        fclose($myfile);
    }
}

function updateHtml($id, $newEntryName, $htmlContent) {
    //ADD PAGE TO DB
    updatePageEntryDB($newEntryName, '{"type":"html"}', $id);
    echo "{\"id\":{$id}}";
    //GENERATE STATIC PAGE
    $myfile = fopen("static/file_" . $id . ".html", "w") or die("Unable to open file!");
    fwrite($myfile, $htmlContent);
    fclose($myfile);
}




if (isset($_POST["operation"]) && $_POST["operation"] == "create" && isset($_POST["type"]) && isset($_POST["pageName"])) {
    //Create page

    //Login cookie check
    if (!isset($_SESSION["user"])) {
        echo '{"error": "Not Logged In"}';
        die();
    }

    if ($_POST["type"] == "document") {

        /* Getting file name */
        $filename = $_FILES['file']['name'];

        /* Upload file */
        if (isset($_FILES['file'])) {
            if (file_exists($_FILES['file']['tmp_name'])) {
                if(isset($loadedPlugins["gdrive"])) {
                    $currentWorkerTimeout = 30;
                    $fileData = "{\"filename\":\"{$_FILES["file"]["name"]}\", \"tmp\":\"{$_FILES["file"]["tmp_name"]}\"}";
                    $output = verifiedExecution($_SESSION["user"], "gdrive", "upload", "FILE\n$fileData");
                    unlink($_FILES["file"]["tmp_name"]);
                    $currentWorkerTimeout = $defaultWorkerTimeout;

                    //ADD PAGE TO DB
                    $dbId = createPageEntryDB($_POST["pageName"], "{\"type\":\"document\",\"gdriveID\":\"$output\"}");
                    echo "{\"id\":{$dbId}}";

                    //GENERATE STATIC PAGE
                    $myfile = fopen("static/file_" . $dbId . ".html", "w") or die("Unable to open file!");
                    $gdrive_file_id = $output;
                    fwrite($myfile, "<iframe src='https://drive.google.com/file/d/{$gdrive_file_id}/preview' width='100%' height='100%' style='border:0 !important;'></iframe>");
                    fclose($myfile);
                }
            }
        }
    }
    else if ($_POST["type"] == "html" && isset($_POST["contentHtml"])) {
        $dbId = createPageEntryDB($_POST["pageName"], '{"type":"html"}');
        echo "{\"id\":{$dbId}}";

        //GENERATE STATIC PAGE
        $myfile = fopen("static/file_" . $dbId . ".html", "w") or die("Unable to open file!");

        fwrite($myfile, "<div class=\"d-flex written-page-body\" ><div class='written-page-block'>".$_POST["contentHtml"]."</div></div>");
        fclose($myfile);
    }
}
else if (isset($_POST["operation"]) && $_POST["operation"] == "list") {
    //Public method
    echo json_encode(getPageList());
}
else if (isset($_POST["operation"]) && $_POST["operation"] == "get" && isset($_POST["id"])) {
    //Public method
    echo json_encode(getPageById(intval($_POST["id"])));;
}
else if (isset($_POST["operation"]) && $_POST["operation"] == "delete" && isset($_POST["id"])) {
    //Login cookie check
    if (!isset($_SESSION["user"])) {
        echo '{"error": "Not Logged In"}';
        die();
    }
    $jsonArrayString = "";
    deletePage(intval($_POST["id"]));
    if(file_exists("static/file_".$_POST["id"].".html"))unlink("static/file_".$_POST["id"].".html");
    echo "{\"status\":\"deleted\"}";
    //TODO: De sters fisierele din DRIVE
}
else if (isset($_POST["operation"]) && $_POST["operation"] == "update" && isset($_POST["type"]) && isset($_POST["pageName"]) && isset($_POST["id"])) {
    //Create page

    //Login cookie check
    if (!isset($_SESSION["user"])) {
        echo '{"error": "Not Logged In"}';
        die();
    }

    if ($_POST["type"] == "document") {
        updateDocument($_POST["id"], $_POST["pageName"]);
    }
    else if ($_POST["type"] == "html" && isset($_POST["contentHtml"])) {
        updateHtml($_POST["id"], $_POST["pageName"], $_POST["contentHtml"]);
    }
}
