<?php

require_once('utils.php');

admin_do(function ($conn) {
  $name = $_GET['name'];

  $query = "select * from $name";

  $stmt = $conn->prepare($query); 
  $stmt->execute(); 
  $row = $stmt->fetchall(PDO::FETCH_ASSOC);
  print_r(json_encode($row));
});

