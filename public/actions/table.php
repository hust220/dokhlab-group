<?php

$name = $_GET['name'];

require_once(__DIR__.'/utils.php');

if ($name == "events") {
  anyone_do(function ($conn) {
    global $name;
    $query = "select * from $name";

    $stmt = $conn->prepare($query); 
    $stmt->execute(); 
    $row = $stmt->fetchall(PDO::FETCH_ASSOC);
//    print_r(json_encode($row));
    print_r(json_encode(utf8ize($row)));
  });
} else {
  admin_do(function ($conn) {
#  anyone_do(function ($conn) {
    global $name;
    $query = "select * from $name";

    $stmt = $conn->prepare($query); 
    $stmt->execute(); 
    $row = $stmt->fetchall(PDO::FETCH_ASSOC);
    print_r(json_encode(utf8ize($row)));
  });
}
