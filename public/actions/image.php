<?php

#header("Access-Control-Allow-Origin: *");

require_once(__DIR__.'/utils.php');

init_head();

$name = $_GET['name'];

$ext = pathinfo($name, PATHINFO_EXTENSION);

$sysTempDir = __DIR__."/../../data/images";

// open the file in a binary mode
$filePath = "$sysTempDir/$name";
$fp = fopen($filePath, 'rb');

// send the right headers
header("Content-Type: image/$ext");
header("Content-Length: " . filesize($filePath));

// dump the picture and stop the script
fpassthru($fp);
exit;

