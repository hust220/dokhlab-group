<?php

header("Access-Control-Allow-Origin: *");

require_once('config.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = 'select allos_tasks.id, title, users.username, userid, tsubmit, tprocess, tfinish, status, receptor, source_chain, source_residue, target_chain, target_residue, k, cutoff, threshold, chains from allos_tasks left join users on allos_tasks.userid=users.id order by tsubmit desc';

    $stmt = $conn->prepare($query); 
    $stmt->execute(); 
    $row = $stmt->fetchall(PDO::FETCH_ASSOC);
    $result = ['tasks' => $row];
    print_r(json_encode($result));
}
catch(PDOException $e) {
    echo "Check failed: " . $e->getMessage();
}
$conn = null;

