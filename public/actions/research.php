<?php

header("Access-Control-Allow-Origin: *");
header('X-Frame-Options: GOFORIT');

echo file_get_contents("https://docs.google.com/document/d/e/2PACX-1vSkcgDSUJqQTPuskj-GhPdeuD4xr-Y-MZuuIDKa32MsJ-1m0Hdhr53ybL9-Co7RP-n_JsK8R7R0EcIr/pub?embedded=true");

