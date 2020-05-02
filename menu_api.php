<?php
require "dataStore.php";

session_name("Private");
session_start();

function getPage($id, &$name, &$link) {
    $page = getPageById($id);
    $name = $page["name"];
    $link = $page["link"];
}

if(isset($_POST["operation"])) {
    if($_POST["operation"] === "updateStructure") {
        if(!isset($_SESSION["user"])) {
            echo "{\"error\": \"Not Logged In\"}";
            die();
        }
        if(isset($_POST["data"])){
            $file = fopen("static/menu_struct.json","w");
            fwrite($file, $_POST["data"]);
            fclose($file);
        }
    }
    else if($_POST["operation"] === "regenerate") {
        if(!isset($_SESSION["user"])) {
            echo "{\"error\": \"Not Logged In\"}";

            die();
        }
        $navListElements = "";
        //read structure file
        $structureFile = fopen("static/menu_struct.json","r");
        $structureString = fread($structureFile,filesize("static/menu_struct.json"));
        fclose($structureFile);
        $structureArray = json_decode($structureString);
        if(is_array($structureArray)) {
            foreach ($structureArray as &$value) {
                if(is_int($value)) {
                    $pageName = "";
                    $pageLink = "";
                    getPage($value, $pageName, $pageLink);
                    $navListElements .= "<li class=\"nav-item\"> <a class=\"nav-link\" href=\"{$pageLink}\">{$pageName}</a> </li>";

                }
                else if(is_object($value)) {
                    if(isset($value->name) && is_string($value->name) && isset($value->pages) && is_array($value->pages)) {
                        //dropdown: object $value
                        $dropdownElements = "";
                        foreach ($value->pages as $pageID) {
                            $pageName = "";
                            $pageLink = "";
                            getPage($pageID, $pageName, $pageLink);
                            $dropdownElements .= "<a class=\"dropdown-item\" href=\"{$pageLink}\">{$pageName}</a>";
                        }
                        $navListElements .= "
                            <li class=\"nav-item dropdown\">
                                <a class=\"nav-link dropdown-toggle\" id=\"navbarDropdown\" role=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                    {$value->name}
                                </a>
                                <div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdown\">
                                    {$dropdownElements}
                                </div>
                            </li>
                        ";
                    }
                }
            }
        }
        //print to file
        $fileInstance = fopen("static/menu.html", "w");
        $fileContents = "<ul class=\"navbar-nav mr-auto\">{$navListElements}</ul>";
        fwrite($fileInstance, $fileContents);
        fclose($fileInstance);
    }
    else if($_POST["operation"] === "getStructure") {
        if(!isset($_SESSION["user"])) {
            echo "{\"error\": \"Not Logged In\"}";
            die();
        }
        $structureString = "";
        if(file_exists("static/menu_struct.json")){
            $structureFile = fopen("static/menu_struct.json","r");
            $structureString = fread($structureFile,filesize("static/menu_struct.json"));
            fclose($structureFile);
        }
        else {
            $structureString = "[]";
        }

        echo $structureString;
    }
}