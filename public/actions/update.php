<?php

require_once('utils.php');

admin(function ($conn) {
  $conds = [];
  foreach ($_POST as $k => $v) {
    if ($k == 'table') {
      $table = $v;
    } elseif ($k == 'id') {
      $id = $v;
    } else {
      $q = $conn->quote($v);
      $conds[] = "$k=$q";
    }
  }
  $condition = implode(", ", $conds);
  $query = "update $table set $condition where id='$id'";
  $conn->exec($query);
  echo "Update successfully";
});

