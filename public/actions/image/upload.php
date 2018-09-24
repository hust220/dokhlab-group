<?php

require_once('../utils.php');

admin_do(function ($conn) {
//  $name = $_POST['name'];
  $name = $_FILES['file']['name'];
  $tmp_name = $_FILES['file']['tmp_name'];

  $tmpFile = tempnam(sys_get_temp_dir(), '');
  rename($tmp_name, $tmpFile);

  echo json_encode(['name'=>$name, 'tmp_name'=>$tmpFile]);
//  echo json_encode(['name'=>$name, 'tmp_name'=>$tmp_name]);

});
