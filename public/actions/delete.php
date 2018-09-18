<?php

require_once('utils.php');

admin(function ($conn) {
  $conds = [];
  foreach ($_GET as $k => $v) {
    if ($k == 'table') {
      $table = $v;
    } else {
      $q = $conn->quote($v);
      $conds[] = "$k=$q";
    }
  }
  $condition = implode(" and ", $conds);
  $query = "delete from $table where $condition";
  $conn->exec($query);
  echo "Delete successfully";
});

