<?php

require_once('utils.php');

admin_do(function ($conn) {

  $name = $_GET['name'];

  $ext = pathinfo($name, PATHINFO_EXTENSION);

  $sysTempDir = __DIR__."/../../data/images";

  // open the file in a binary mode
  $filePath = "$sysTempDir/$name";

  $cmd = "rm $filePath";
  system($cmd);

});
