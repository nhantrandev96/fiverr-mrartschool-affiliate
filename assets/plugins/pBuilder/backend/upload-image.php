<?php

$error = false;
$imageMoveSuccessful = false;

if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $error = true;
}

$info = getimagesize($_FILES['file']['tmp_name']);
if ($info === FALSE) {
    $error = true;
}

if (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
    $error = true;
}

if ( ! $error) {
    $imageMoveSuccessful = move_uploaded_file($_FILES['file']['tmp_name'], __DIR__.'/../storage/uploads/'.$_FILES['file']['name']);
}

if ($error || ! $imageMoveSuccessful) {
    http_response_code(500);
    return;
}

$url = 'http://'.$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
echo str_replace('backend/upload-image.php', 'storage/uploads/' . $_FILES['file']['name'], $url);