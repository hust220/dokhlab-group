<?php

require_once(__DIR__.'/../utils.php');

admin_do(function ($conn) {
  $name = $_GET['name'];

  $sysTempDir = __DIR__."/../../../data";
  $filePath = "$sysTempDir/$name";

  unlink($filePath);

});
