<?php

header("Access-Control-Allow-Origin: *");

$id = $_GET['id'];

$log = file_get_contents(__DIR__."/../../daemon/exec/$id/log.txt");

echo $log;

