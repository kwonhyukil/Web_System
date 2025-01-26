<?php
session_start();
// 데이터베이스 연결 정보
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    die('아이디와 비밀번호를 입력해주세요.');
}

$sql = "SELECT username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // 비밀번호 검증
    if (password_verify($password, $user['password'])) {
        // 로그인 성공
        $_SESSION['username'] = $username; // 세션 저장
        header('Location: ../public/main_menu.html');
        exit();
    } else {
        // 비밀번호 불일치
        die('아이디 또는 비밀번호가 잘못되었습니다.');
    }
} else {
    // 사용자 정보 없음
    die('아이디 또는 비밀번호가 잘못되었습니다.');
}

// 자원 해제
$stmt->close();
$conn->close();
?>