<?php

require_once(__DIR__.'/utils.php');

anyone_do(function ($conn) {
  try {
    if (isset($_SESSION['user'])) {
      exit(json_encode(['status'=>1, 'user'=>$_SESSION['user']]));
    }
    else {
      exit(json_encode(['status'=>0, 'msg'=>'No user']));
    }
  }
  catch(PDOException $e) {
    exit(json_encode(['status'=>0, 'msg'=>$e->getMessage()]));
  }

});

