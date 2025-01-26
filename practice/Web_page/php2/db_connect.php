<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

// 데이터를 주고받기 위한 MySQL 연결 객체 생성
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connection_error);
}
?>