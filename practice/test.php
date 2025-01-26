<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

// MySQL 연결
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

if ($password !== $password2) {
    die('비밀번호가 일치하지 않습니다.');
}
if (empty($username) || empty($password) || empty($password2) || empty($name) || empty($email)) {
    die('모든 필드를 입력해주세요.');
}
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$sql = "INSERT INTO users (username, password, password2, name, email) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);



?>