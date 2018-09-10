<?php

header("Access-Control-Allow-Origin: *");

require_once('config.php');

try {
    $id = $_GET['id'];

    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['item'])) {
        $item = $_GET['item'];

        $query = "select id, $item from allos_tasks where id=$id";

        $stmt = $conn->prepare($query); 
        $stmt->execute(); 
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item == 'output') $row['output'] = file_get_contents(__DIR__."/../../daemon/blobs/".$row['output']);
        if ($item == 'log') $row['log'] = file_get_contents(__DIR__."/../../daemon/blobs/".$row['log']);

        $content = $row[$item];

        if (isset($_GET['suffix'])) {
            $suffix = $_GET['suffix'];

            header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
            header("Cache-Control: public"); // needed for internet explorer
            header("Content-Type: application/octet-stream");
            header("Accept-Ranges: bytes");
            header("Content-Disposition: attachment; filename=$id.$item.$suffix");
        }

        echo $content;
    }
    else {
        $query = "select * from allos_tasks where id=$id";

        $stmt = $conn->prepare($query); 
        $stmt->execute(); 
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

//        $row['output'] = file_get_contents(__DIR__."/../../daemon/blobs/".$row['output']);
//        $row['log'] = file_get_contents(__DIR__."/../../daemon/blobs/".$row['log']);

        print_r(json_encode($row));
    }
}
catch(PDOException $e) {
    echo "Check failed: " . $e->getMessage();
}
$conn = null;

