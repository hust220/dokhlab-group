<?php

require_once(__DIR__.'/utils.php');

admin_do(function ($conn) {
  $conds = [];
  foreach ($_POST as $k => $v) {
    if ($k == '__table') {
      $table = $v;
    } else {
      if ($v != '') {
        $q = $conn->quote($v);
        $conds[] = "$k=$q";
      }
    }
  }
  $condition = implode(" and ", $conds);
  $query = "delete from $table where $condition";
  $conn->exec($query);
  echo "Delete successfully";
});

