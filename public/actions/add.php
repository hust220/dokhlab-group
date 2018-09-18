<?php

require_once('utils.php');

admin(function ($conn) {
  $keys = [];
  $values = [];
  foreach ($_POST as $k => $v) {
    if ($k == 'table') {
      $table = $v;
    } else {
      $q = $conn->quote($v);
      $keys[] = $k;
      $values[] = $q;
    }
  }
  $keys_sentence = implode(", ", $keys);
  $values_sentence = implode(", ", $values);
  $query = "insert into $table($keys_sentence) values($values_sentence)";
  $conn->exec($query);
  echo "Add Successfully";
});

