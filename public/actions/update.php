<?php

require_once('utils.php');

admin_do(function ($conn) {
  $return = [];

  $conds = [];
  foreach ($_POST as $k => $v) {
    if ($k == '__table') {
      $table = $v;
    } elseif ($k == '__key') {
      $key = $v;
    } else {
      if ($v != '') {
        if ($k == 'password' || $k == 'passwd') {
          $v = md5($v);
        }

        $return[$k] = $v;

        $q = $conn->quote($v);
        if ($k == $key) {
          $value = $q;
        } else {
          $conds[] = "$k=$q";
        }
      }
    }
  }
  $condition = implode(", ", $conds);
  $query = "update $table set $condition where $key=$value";
  $conn->exec($query);

  echo json_encode($return);
});

