<?php

require_once('../utils.php');

admin_do(function ($conn) {
  $oldName = $_GET['oldname'];
  $newName = $_GET['newname'];

  $sysTempDir = __DIR__."/../../../data";

  $oldPath = "$sysTempDir/$oldName";
  $newPath = "$sysTempDir/$newName";

  rename($oldPath, $newPath);

});