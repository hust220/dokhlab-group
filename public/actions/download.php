<?php

#header("Access-Control-Allow-Origin: *");

require_once('utils.php');

init_head();

$name = $_GET['name'];
$sysTempDir = __DIR__."/../../data";
$file = "$sysTempDir/$name";

#$ext = pathinfo($name, PATHINFO_EXTENSION);


#header("Content-type:application/pdf");
#header("Content-Disposition:attachment;filename='downloaded.pdf'");
#readfile($filePath);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
readfile($file);

