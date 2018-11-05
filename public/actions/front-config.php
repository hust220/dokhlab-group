<?php

require_once('utils.php');

anyone_do(function ($conn) {
  $filePath = __DIR__."/../../data/front-config.json";

  echo file_get_contents($filePath);
//  echo json_decode(file_get_contents($filePath));
});
