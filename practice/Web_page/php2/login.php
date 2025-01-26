<?php
session_start(); // 세션 시작

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

$conn = new mysqli($host, $username, $password, $database);

if ($conn -> connect_error) {
    die ('데이터베이스 연결 실패: ' . $conn -> connect_error);
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    die('아이디와 비밀번호를 입력해주세요.');
}

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

} else {
    die('존재하지 않는 사용자입니다.');
}

if (password_verify($password, $user['password'])) {
    echo '비밀번호가 일치합니다.';
} else {
    die('비밀번호가 틀렸습니다.');
}

$_SESSION['username'] = $username; // 세션에 사용자 저장
header('Location: ../html2/welcome.html'); // 다음 페이지로 이동
exit();

$stmt->close();
$conn->close();

?>
