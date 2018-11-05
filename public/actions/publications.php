<?php

require_once('utils.php');

anyone_do(function($conn){
	$query = 'select * from pubs';

	$stmt = $conn->prepare($query); 
	$stmt->execute(); 
	$row = $stmt->fetchall(PDO::FETCH_ASSOC);
	print_r(json_encode(utf8ize($row)));
});
