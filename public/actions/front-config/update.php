<?php

require_once(__DIR__.'/../utils.php');

admin_do(function ($conn) {
  $filePath = __DIR__."/../../../data/front-config.json";
  $config = $_POST['config'];

  file_put_contents($filePath, $config);
});

