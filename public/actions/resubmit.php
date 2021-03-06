<?php

header("Access-Control-Allow-Origin: *");

session_start();

require_once(__DIR__.'/config.php');

try {
  $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $id = $_POST['id'];

  if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user']['id'];
    $userlevel = $_SESSION['user']['level'];

    $query = "select id, userid from allos_tasks where id='$id'";
    $stmt = $conn->prepare($query); 
    $stmt->execute(); 
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userid == $row['userid'] || $userlevel == '10') {
      // Set input
      $input = [];
      foreach ($_POST as $k => $v) {
        if ($k != 'id') {
          $input[$k] = $conn->quote($v);
        }
      }

      $query = "update allos_tasks set status='queued', ".implode(', ', array_map(function ($i) use ($input) { $v=$input[$i]; return "$i=$v"; }, array_keys($input)))." where id='$id'";

      $conn->exec($query);

      header("HTTP/1.1 200 OK");
      echo "Resubmitted successfully";
    } else {
      header("HTTP/1.1 404 No Permissions");
      echo "No permissions";
    }
  } else {
    header("HTTP/1.1 404 No Permissions");
    echo "No permissions";
  }
} catch(PDOException $e) {
  header("HTTP/1.1 404 No Permissions");
  echo "Resubmission failed: " . $e->getMessage();
}

$conn = null;

