<?php
require "./config.php";

$dbConn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($dbConn->connect_error) {
    die("Connection failed: " . $dbConn->connect_error);
}

//User management and authentication
$defaultUserData = "{}";
function addUser(string $username, string $password)
{
    global $dbConn, $defaultUserData;
    $success = false;
    $stmt = $dbConn->prepare("INSERT INTO users(username, password, data) VALUES(?, ?, ?)");
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bind_param("sss", $username, $passwordHash, $defaultUserData);
    return $stmt->execute();
}

function deleteUser(string $username)
{
    global $dbConn;
    $stmt = $dbConn->prepare("DELETE FROM users WHERE username = (?)");
    $stmt->bind_param("s", $username);
    return $stmt->execute();
}

function checkPassword(string $username, string $password)
{
    global $dbConn;
    $result = false;
    $retrievedHash = "";
    $stmt = $dbConn->prepare("SELECT (password) FROM users WHERE username = (?)");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($retrievedHash);
        $stmt->fetch();
        $result = password_verify($password, $retrievedHash);
    }
    $stmt->close();
    return $result;
}

function setUserData(string $username, $data)
{
    global $dbConn;
    $stmt = $dbConn->prepare("UPDATE users SET data = ? where username = ?;");
    $dataString = json_encode($data);
    $stmt->bind_param("ss", $dataString, $username);
    return $stmt->execute();
}

function getUserData(string $username)
{
    global $dbConn;
    $stmt = $dbConn->prepare("SELECT (data) FROM users WHERE username = (?)");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $dataString = "";
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($dataString);
        $stmt->fetch();
    }
    $stmt->close();
    return json_decode($dataString, true);
}

//Pages
function getPageById(int $id)
{
    global $dbConn;
    $found = false;
    $stmt = $dbConn->prepare("SELECT name, link, data FROM pages WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $name = "";
    $link = "";
    $data = "";
    if ($stmt->num_rows > 0) {
        $found = true;
        $stmt->bind_result($name, $link, $data);
        $stmt->fetch();
    }
    $stmt->close();
    if ($found) return array("id" => $id, "name" => $name, "link" => $link, "data" => json_decode($data, true));
    else return null;
}

function setPage($id, $name, $link, $data)
{
    global $dbConn;
    $encodedData = null;
    if($data !== null)$encodedData = json_encode($data);
    $page = null;
    if($id !== null)$page = getPageById($id);
    if ($page === null) {
        if ($id !== null) {
            $stmt = $dbConn->prepare("INSERT INTO pages(id, name, link, data) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $id, $name, $link, $encodedData);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $dbConn->prepare("INSERT INTO pages(name, link, data) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $link, $encodedData);
            $stmt->execute();
            $stmt->close();
            $stmt = $dbConn->prepare("SELECT LAST_INSERT_ID()");
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $autoId = 0;
                $stmt->bind_result($autoId);
                $stmt->fetch();
                $stmt->close();
                return $autoId;
            }
            else $stmt -> close();
            return null;
        }
    } else {
        if ($name !== null) $page["name"] = $name;
        if ($link !== null) $page["link"] = $link;
        if ($data !== null) $page["data"] = $data;
        $stmt = $dbConn->prepare("UPDATE pages SET name = ?, link = ?, data = ? WHERE id = ?");
        if($page["data"] !== null)$encodedData = json_encode($page["data"]);
        else $encodedData = null;
        $stmt->bind_param("sssi", $page["name"], $page["link"], $encodedData, $id);
        $stmt->execute();
        $stmt->close();
    }
    return null;
}

function getPageList() {
    global  $dbConn;
    $pageList = array();
    $stmt = $dbConn -> prepare("SELECT id, name, link, data FROM pages");
    $stmt -> execute();
    $stmt -> store_result();
    for($i = 0; $i < $stmt -> num_rows; $i++) {
        $id = 0;
        $name = "";
        $link = "";
        $dataString = "";
        $stmt -> bind_result($id, $name, $link, $dataString);
        $stmt -> fetch();
        $pageList[] = array("id" => $id, "name" => $name, "link" => $link, "data" => json_decode($dataString, true));
    }
    return $pageList;
}

function deletePage($id) {
    global $dbConn;
    $stmt = $dbConn -> prepare("DELETE FROM pages WHERE id = ?");
    $stmt -> bind_param("i", $id);
    $stmt -> execute();
    $stmt -> close();
}