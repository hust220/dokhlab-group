<?php

#header("Access-Control-Allow-Origin: *");

require_once(__DIR__.'/../utils.php');

init_head();

$filePath = $_GET['path'];
$name = $_GET['name'];

$ext = pathinfo($name, PATHINFO_EXTENSION);

header("Content-Type: image/$ext");
header("Content-Length: " . filesize($filePath));

$fp = fopen($filePath, 'rb');
fpassthru($fp);
exit;

