<?php

require_once('utils.php');

function dir_scan_it($root) {
  $obj = [];
  $obj['name'] = basename($root);
  if (is_dir($root)) {
    $obj['sons'] = [];

    $files = scandir($root);

    foreach ($files as $k => $v) {
      if ($v != '.' && $v != '..') {
        $path = $root."/$v";
        $obj['sons'][] = dir_scan_it($path);
      }
    }
  } else {
    $obj['sons'] = '';
  }
  return $obj;
}

admin_do(function ($conn) {
  require_once('config.php');

  $images_root = __DIR__."/../../data/images";

  echo json_encode(dir_scan_it($images_root));
});

