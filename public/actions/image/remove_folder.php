<?php

function deleteDirectory($dir) {
  if (!file_exists($dir)) {
    return true;
  }

  if (!is_dir($dir)) {
    return unlink($dir);
  }

  foreach (scandir($dir) as $item) {
    if ($item == '.' || $item == '..') {
      continue;
    }

    if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
      return false;
    }

  }

  return rmdir($dir);
}

require_once('../utils.php');

admin_do(function ($conn) {
  $name = $_GET['name'];

  $sysTempDir = __DIR__."/../../../data/images";
  $filePath = "$sysTempDir/$name";

  deleteDirectory($filePath);
});
