<?php

require_once(__DIR__.'/babel_utils.php');
require_once(__DIR__.'/zinc_utils.php');

header("Access-Control-Allow-Origin: *");

header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
header("Cache-Control: public"); // needed for internet explorer
header("Content-Type: text/plain");

$zincid = $_POST['zincid'];
if ($zincid != '') {
  echo babel_convert(zinc_fetch($zincid), 'mol2', 'mol2');
} else {
  $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
  echo babel_convert(file_get_contents($_FILES['file']['tmp_name']), $ext, 'mol2');
}

