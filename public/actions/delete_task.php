<?php

header("Access-Control-Allow-Origin: *");

session_start();

require_once(__DIR__.'/config.php');

try {
  $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (isset($_SESSION['user'])) {
    $id = $_GET['id'];

    $userid = $_SESSION['user']['id'];
    $userlevel = $_SESSION['user']['level'];

    $query = "select id, userid from allos_tasks where id='$id'";
    $stmt = $conn->prepare($query); 
    $stmt->execute(); 
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userid == $row['userid'] || $userlevel == '10') {
      $query = "delete from allos_tasks where id='$id'";
      $conn->exec($query);
      header("HTTP/1.1 200 OK");
      echo "Delete successfully";
    } else {
      header("HTTP/1.1 404 No Permissions");
      echo "No permissions!";
    }
  } else {
    header("HTTP/1.1 404 No Permissions");
    echo "No permissions!";
  }
}
catch(PDOException $e) {
  header("HTTP/1.1 404 No Permissions");
  echo "Delete failed: " . $e->getMessage();
}

$conn = null;

