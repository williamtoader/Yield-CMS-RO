<?php
require "dataStore.php";
$stdin = fopen("php://stdin", "r");
$stdout = fopen("php://stdout", "w");
fputs($stdout, "Username:");
$uname = trim(fread($stdin, 512)," \t\n\r\0\x0B");
fputs($stdout, "Password:");
$pass = trim(fread($stdin, 512)," \t\n\r\0\x0B");
addUser($uname, md5($pass));
fclose($stdin);
fclose($stdout);