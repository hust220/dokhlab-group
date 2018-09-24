<?php

require_once('utils.php');

admin_do(function ($conn) {

  $name = $_POST['name'];

  $img = $_FILES['img']['tmp_name'];

  $ext = pathinfo($name, PATHINFO_EXTENSION);

  $sysTempDir = __DIR__."/../../data/images";

  // open the file in a binary mode
  $filePath = "$sysTempDir/$name";

  $cmd = "cp $img $filePath";
  system($cmd);

});
