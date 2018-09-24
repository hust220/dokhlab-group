<?php

require_once('utils.php');

anyone_do(function ($conn) {
    $username = $_POST['username'];
    $provided_password = $_POST['password'];

    $username_q = $conn->quote($username);

    $query = "select * from users where username=$username_q";

    $stmt = $conn->prepare($query); 
    $stmt->execute(); 
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $password = $row['password'];

        if (md5($provided_password) != $password) {
            exit(json_encode(['status'=>0, 'msg'=>'Password Not Correct!']));
        }
        else {
            $_SESSION['user'] = $row;
            echo json_encode(['status'=>1, 'user'=>$row]);
        }
    }
    else {
        exit(json_encode(['status'=>0, 'msg'=>'Username doesn\'t exist!']));
    }
});

#session_start();
#
#require_once('config.php');
#
#try {
#    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
#    // set the PDO error mode to exception
#    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
#
#}
#catch(PDOException $e) {
#    exit(json_encode(['status'=>0, 'msg'=>$e->getMessage()]));
#}
#
#$conn = null;

