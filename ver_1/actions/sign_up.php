<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

$conn = new mysqli($host, $username, $password, $database);

// 폼 데이터 가져오기
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

if (empty($_id) || empty($password) || empty($password2) || empty($name) || empty($email)) {
    die('모든 필드를 입력하세요.');
}

if ($password !== $password2) {
    die('비밀번호가 일치하지 않습니다.');
}

$sql = insert into users (username, password, password2, name, email) values (?,?,?,?,?);
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssss', $username, $password, $password2, $name, $email);

if ($stmt->execute()) {
    echo '회원가입 성공';
    header('Location: welcome.html');
} else {
    echo '회원가입 실패' . $stmt->error;
}
