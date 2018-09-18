<?php

function admin($cb) {

  header("Access-Control-Allow-Origin: *");

  session_start();

  require_once('config.php');

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_SESSION['user'])) {
      $userid = $_SESSION['user']['id'];
      $userlevel = $_SESSION['user']['level'];

      if ($userlevel == '10') {
        $cb($conn);
        header("HTTP/1.1 200 OK");
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

}

