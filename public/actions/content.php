<?php

header("Access-Control-Allow-Origin: *");

$name = $_GET['name'];

$sysTempDir = __DIR__."/../../data/contents";


echo file_get_contents("$sysTempDir/$name.html");

#if (empty($format)) {
#    $format = pathinfo($name, PATHINFO_EXTENSION);
#}
#
#if (empty($format)) {
#    echo file_get_contents("$sysTempDir/$name");
#}
#else {
#    header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
#    header("Cache-Control: public"); // needed for internet explorer
#    header("Content-Type: application/octet-stream");
#    header("Accept-Ranges: bytes");
#    header("Content-Disposition: attachment; filename=$name");
#    $sysTempDir = __DIR__."/../../daemon/blobs";
#    echo file_get_contents("$sysTempDir/$name");
#}
