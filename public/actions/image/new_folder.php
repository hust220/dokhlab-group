<?php

require_once('../utils.php');

admin_do(function ($conn) {
  $name = $_GET['name'];

  $sysTempDir = __DIR__."/../../../data/images";

  $filePath = "$sysTempDir/$name";

  mkdir($filePath, 0777, true);

});
