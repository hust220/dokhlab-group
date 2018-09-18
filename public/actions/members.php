<?php

header("Access-Control-Allow-Origin: *");

require_once('config.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = 'select * from mems join mem_info where mems.mem_id=mem_info.mem_id';

    $stmt = $conn->prepare($query); 
    $stmt->execute(); 
    $row = $stmt->fetchall(PDO::FETCH_ASSOC);
    print_r(json_encode($row));
}
catch(PDOException $e) {
    echo "Check failed: " . $e->getMessage();
}
$conn = null;

