<?php

header("Access-Control-Allow-Origin: *");
//header('X-Frame-Options: GOFORIT');
//header('X-Frame-Options: SAMEORIGIN');

#echo file_get_contents("https://docs.google.com/document/d/e/2PACX-1vQhNi9UyjZrzWSnWXEpzKwFIANESYx7gm9Cm1_YZafa_WB8XgjMqvuIC4IO69UbcCQLNttCeXsnqsU1/pub?embedded=true");

echo file_get_contents($_GET["src"]);

