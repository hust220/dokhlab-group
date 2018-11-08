<?php

header("Access-Control-Allow-Origin: *");

session_start();

require_once(__DIR__.'/config.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_POST['id'];
    $path_cutoff = $_POST['path_cutoff'];

    $query = "update allos_tasks set paths=NULL, path_cutoff='$path_cutoff' where id='$id'";

    $conn->exec($query);
    echo "Submitted successfully";
}
catch(PDOException $e) {
    echo "Submission failed: " . $e->getMessage();
}
$conn = null;

//$cp = __FILE__;
//$wp = "$cp/../daemon";
//
//if (!file_exists($wp)) {
//    mkdir($wp, 0777, true);
//}


