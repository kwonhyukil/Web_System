<?php
// 세션 시작
session_start();

// 데이터베이스 연결 설정
$host = 'localhost'; // 호스트 이름
$db = 'il_database'; // 데이터베이스 이름
$user = 'root'; // 사용자 이름
$pass = 'gsc1234!@#$'; // 비밀번호
$charset = 'utf8mb4'; // 문자 인코딩 설정

// PDO 연결 옵션 설정
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // PDO 객체 생성 및 데이터베이스 연결
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("데이터베이스 연결 실패: " . $e->getMessage());
}

// POST 요청 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id']);
    $password = trim($_POST['password']);

    // 필드 값 확인
    if (empty($student_id) || empty($password)) {
        echo "<script>alert('모든 필드를 입력해주세요.'); history.back();</script>";
        exit();
    }

    // 사용자 인증
    $sql = "SELECT * FROM users WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':student_id' => $student_id]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // 세션에 사용자 정보 저장
        $_SESSION['loggedin'] = true;
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['student_id'] = $user['student_id'];

        // 공지사항 및 시간표 페이지로 리다이렉트
        header("Location: notice_schedule.php");
        exit();
    } else {
        // 로그인 실패
        echo "<script>alert('학번 또는 비밀번호가 잘못되었습니다.'); history.back();</script>";
        exit();
    }
} else {
    echo "<script>alert('잘못된 요청입니다.'); history.back();</script>";
    exit();
}
?>
