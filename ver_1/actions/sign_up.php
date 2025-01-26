<?php
// 데이터베이스 연결 정보
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

// 데이터베이스 연결
$conn = new mysqli($host, $username, $password, $database);

// 연결 확인
if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

// 폼 데이터 가져오기
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

// 입력값 유효성 검사
if (empty($username) || empty($password) || empty($password2) || empty($name) || empty($email)) {
    die('모든 필드를 입력하세요.');
}

if ($password !== $password2) {
    die('비밀번호가 일치하지 않습니다.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('유효한 이메일 형식이 아닙니다.');
}

// 비밀번호 해싱
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL 삽입 쿼리 준비
$sql = "INSERT INTO users (username, password, name, email) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die('쿼리 준비 실패: ' . $conn->error);
}

$stmt->bind_param('ssss', $username, $hashed_password, $name, $email);

// 쿼리 실행 및 결과 확인
if ($stmt->execute()) {
    echo '회원가입 성공';
    header('Location: ../public/welcome.html');
} else {
    // 중복된 아이디 또는 이메일 에러 처리
    if ($conn->errno === 1062) { // 1062: Duplicate entry
        die('중복된 아이디 또는 이메일이 있습니다.');
    } else {
        die('회원가입 실패: ' . $stmt->error);
    }
}

// 자원 해제 및 연결 종료
$stmt->close();
$conn->close();
?>
