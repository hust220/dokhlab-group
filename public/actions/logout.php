<?php

require_once(__DIR__.'/utils.php');

anyone_do(function ($conn) {
  session_start();
  session_destroy();
});


