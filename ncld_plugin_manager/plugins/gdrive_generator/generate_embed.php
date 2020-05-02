<?php
require "gdrive_connect.php";
$stdin = fopen('php://stdin', 'r');
$stdout = fopen('php://stdout', 'w');
$callType = fgets($stdin);

$fileData = fgets($stdin, 1024);
$fileObj = json_decode($fileData, true);

/* Getting file name */
$filename = $fileObj["filename"];
$client = getClient();
$service = new Google_Service_Drive($client);
$fileMetadata = new Google_Service_Drive_DriveFile(array(
    'name' => basename($filename)));

$mime_type = mime_content_type($fileObj["tmp"]);
$content = file_get_contents($fileObj["tmp"]);

$file = $service->files->create($fileMetadata, array(
    'data' => $content,
    'mimeType' => $mime_type,
    'uploadType' => 'multipart',
    'fields' => 'id'));

$file->setShared(true);
$permission = new Google_Service_Drive_Permission();
$permission->setRole("reader");
$permission->setType("anyone");
$service->permissions->create($file->id, $permission);

$gdrive_file_id = $file->id;
//https://drive.google.com/file/d/{$gdrive_file_id}/preview
fputs($stdout, $gdrive_file_id);
$googleDriveConn = getClient();
//fwrite($stdout, $callType);
fclose($stdin);
fclose($stdout);