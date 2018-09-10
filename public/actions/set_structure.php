<?php

require_once('babel_utils.php');

header("Access-Control-Allow-Origin: *");

header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
header("Cache-Control: public"); // needed for internet explorer
header("Content-Type: text/plain");

$pdbid = $_POST['pdbid'];
if (!empty($pdbid)) {
//  echo babel_convert(file_get_contents("http://files.rcsb.org/download/$pdbid.cif"), 'cif', 'pdb');
  $ifile = tempnam(sys_get_temp_dir(), '');
  file_put_contents($ifile, file_get_contents("http://files.rcsb.org/download/$pdbid.cif"));
  echo set_file($ifile, 'cif');
} else {
  $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
//  echo babel_convert_file($_FILES['file']['tmp_name'], $ext, 'pdb');
  echo set_file($_FILES['file']['tmp_name'], $ext);
}

