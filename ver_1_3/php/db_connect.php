<?php

// 데이터베이스 연결함수

function connectdatabase() {
    $host = "localhost";
    $username = "root";
    $passward = "";
    $database = "user_registration";

    $conn = new mysqli($host, $username, $passward, $database);

    if ($conn->connect_error){
        die("데이터베이스 연결실패" . $conn->connect_error);
    }

    // 연결 객체 반환
    return $conn;
}

?>