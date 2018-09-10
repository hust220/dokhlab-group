<?php

header("Access-Control-Allow-Origin: *");

function zinc_fetch($id) {
  $html = file_get_contents("http://zinc.docking.org/substance/$id");
  $matches = [];
  preg_match('/"(http:\/\/[^"]+&f=m)"/', $html, $matches);
  $url = $matches[1];

//  header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
//  header("Cache-Control: public"); // needed for internet explorer
//  header("Content-Type: application/octet-stream");
//  header("Accept-Ranges: bytes");
//  header("Content-Disposition: attachment; filename=$id.mol2");
  return file_get_contents($url);
}
