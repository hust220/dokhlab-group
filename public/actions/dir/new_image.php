<?php

require_once('../utils.php');

admin_do(function ($conn) {
  $name = $_POST['name'];
  $tmpFilePath = $_FILES['file']['tmp_name'];

  $sysTempDir = __DIR__."/../../../data";

  // open the file in a binary mode
  $filePath = "$sysTempDir/$name";

  rename($tmpFilePath, $filePath);

});
