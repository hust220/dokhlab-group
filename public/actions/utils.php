<?php

function init_session() {
  session_start();

  $time = $_SERVER['REQUEST_TIME'];

//  echo "hi0\n";
//  echo $time."\n";

  /**
   * for a 30 minute timeout, specified in seconds
   */
  $timeout_duration = 1800;

  /**
   * Here we look for the user's LAST_ACTIVITY timestamp. If
   * it's set and indicates our $timeout_duration has passed,
   * blow away any previous $_SESSION data and start a new one.
   */
  if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
//      echo $time - $_SESSION['LAST_ACTIVITY'];
//      print_r($_SESSION);
      session_unset();
      session_destroy();
      session_start();
//      print_r($_SESSION);
    }
  }
//  echo 'hi1';

  /**
   * Finally, update LAST_ACTIVITY so that our timeout
   * is based on it and not the user's login time.
   */
  $_SESSION['LAST_ACTIVITY'] = $time;
//  echo 'hi2';
}

function init_head() {
  $origin = $_SERVER['HTTP_ORIGIN'];
  header("Access-Control-Allow-Origin: $origin");
  header("Access-Control-Allow-Credentials: true");
}


function anyone_do($cb) {
  init_head();
  init_session();

  require_once(__DIR__.'/config.php');

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $cb($conn);
  } catch(PDOException $e) {
    header("HTTP/1.1 404 No Permissions");
    echo "No permissions!";
  }

  $conn = null;
}

function user_do($cb) {
  anyone_do(function ($conn) use($cb) {
    if (isset($_SESSION['user'])) {
//    echo 'hi0.1';
      $cb($conn);
    } else {
//    echo 'hi0.2';
      header("HTTP/1.1 404 No Permissions");
      echo "No permissions!";
    }
  });
}

function admin_do($cb) {
  user_do(function ($conn) use($cb) {
//    echo 'hi1';
    $userid = $_SESSION['user']['id'];
    $userlevel = $_SESSION['user']['level'];

    if ($userlevel == '10') {
//    echo 'hi2';
      $cb($conn);
      header("HTTP/1.1 200 OK");
    } else {
      header("HTTP/1.1 404 No Permissions");
      echo "No permissions!";
    }
  });
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

function sqlite_fetch_all($queryResultSet){
	$multiArray = array();
	$count = 0;
	while($row = $queryResultSet->fetchArray(SQLITE3_ASSOC)){
		foreach($row as $i=>$value) {
			$multiArray[$count][$i] = $value;
		}
		$count++;
	}
	return $multiArray;
}



