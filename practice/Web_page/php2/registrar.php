<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

// 데이터를 주고받기 위한 MySQL 연결 객체 생성
// 이 연결을 $conn 변수에 저장
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connection_error);
}

// 폼 데이터 가져오기
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

if ($password != $password2) {
    die('비밀번호가 일치하지 않습니다.');
}

// 사용자가 모든 필드를 입력했는지 확인
if (empty($username) || empty($password) || empty($password2) || empty($name) || empty($email)) {
    die('모두 입력해주세요.');
}

// 비밀번호 암호화
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// 데이터 삽입
$sql = "insert into users (username, password, password2, name, email) values (?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssss', $username, $hashed_password, $password2, $name, $email); 

if ($stmt->execute()) {
    echo '회원가입 성공';
    header('Location: welcome.html')
} else {
    echo '회원가입 실패' . $stmt -> error;
}

// MySQL 연결 닫기
$stmt->close();
$conn->close();


?>