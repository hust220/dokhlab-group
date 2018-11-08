<?php

require_once(__DIR__.'/../utils.php');

admin_do(function ($conn) {
  $sysTempDir = __DIR__."/../../../data";

  $oldPath = $_GET['path'];

  $newName = $_GET['name'];
  $newPath = "$sysTempDir/$newName";

  rename($oldPath, $newPath);

});
