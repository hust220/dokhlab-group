<?php

require_once('utils.php');

anyone_do(function ($conn) {
  session_start();
  session_destroy();
});


