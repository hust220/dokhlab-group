<?php

require_once(__DIR__.'/utils.php');

admin_do(function ($conn) {
  $return = [];

  $keys = [];
  $values = [];
  foreach ($_POST as $k => $v) {
    if ($k == '__table') {
      $table = $v;
    } else {
      if ($v != '') {
        if ($k == 'password' || $k == 'passwd') {
          $v = md5($v);
        }

        $return[$k] = $v;

        $q = $conn->quote($v);
        $keys[] = $k;
        $values[] = $q;
      }
    }
  }
  $keys_sentence = implode(", ", $keys);
  $values_sentence = implode(", ", $values);
  $query = "insert into $table($keys_sentence) values($values_sentence)";
#  print_r($query);
  $conn->exec($query);

  echo json_encode($return);
});

