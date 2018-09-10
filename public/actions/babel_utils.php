<?php

require_once('config.php');

function set_file($file, $ext) {
  $ofile = tempnam(__DIR__."/../../daemon/blobs", '').".$ext";

  $cmd = "cp $file $ofile";
  system($cmd);

//  $text = file_get_contents($file);
//  file_put_contents($ofile, $text);

  // Set priviledge of output files
  $cmd = "chmod 777 $ofile";
  system($cmd);

  return pathinfo($ofile, PATHINFO_BASENAME);
}

function babel_convert_file($file, $iformat, $oformat) {
  global $config;

  $ofile = tempnam(__DIR__."/../../daemon/blobs", '');
  $cmd = $config['babel_path']." -i$iformat $file -o$oformat $ofile";
  system($cmd);

  // Fix bugs in pdb format caused by babel
  if ($oformat == 'pdb') {
    $tmpFile = tempnam(sys_get_temp_dir(), '');
    $cmd = "python ".__DIR__."/merge_chains.py $ofile $tmpFile; mv $tmpFile $ofile";
    system($cmd);
  }

  // Set priviledge of output files
  $cmd = "chmod 777 $ofile";
  system($cmd);

  return pathinfo($ofile, PATHINFO_BASENAME);
}

function babel_convert($content, $iformat, $oformat) {
  $ifile = tempnam(sys_get_temp_dir(), '');
  file_put_contents($ifile, $content);
  return babel_convert_file($ifile, $iformat, $oformat);
}

