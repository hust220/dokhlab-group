<?php

$name = $_GET['name'];

require_once('utils.php');

if ($name == "events") {
  anyone_do(function ($conn) {
    global $name;
    $query = "select * from $name";

    $stmt = $conn->prepare($query); 
    $stmt->execute(); 
    $row = $stmt->fetchall(PDO::FETCH_ASSOC);
    print_r(json_encode($row));
  });
} else {
  admin_do(function ($conn) {
    global $name;
    $query = "select * from $name";

    $stmt = $conn->prepare($query); 
    $stmt->execute(); 
    $row = $stmt->fetchall(PDO::FETCH_ASSOC);
    print_r(json_encode($row));
  });
}
