<?php

global $config, $servername, $username, $password, $database;

$config = json_decode(file_get_contents(__DIR__."/../../config.json"), true);

$servername = $config['db']['host'];
$username   = $config['db']['user'];
$password   = $config['db']['password'];
$database   = $config['db']['database'];

