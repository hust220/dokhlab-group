<?php

header("Access-Control-Allow-Origin: *");

$name = $_GET['name'];

require_once('config.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "select * from $name";

    $stmt = $conn->prepare($query); 
    $stmt->execute(); 
    $row = $stmt->fetchall(PDO::FETCH_ASSOC);
    print_r(json_encode($row));
}
catch(PDOException $e) {
    echo "Check failed: " . $e->getMessage();
}
$conn = null;

