<?php
session_start();

// 데이터베이스 연결 설정
$host = 'localhost';
$db = 'il_database';
$user = 'root';
$pass = 'gsc1234!@#$';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("데이터베이스 연결 실패: " . $e->getMessage());
}

// POST 요청으로 전달된 학번과 비밀번호 받기
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = htmlspecialchars(trim($_POST['student_id']));
    $password = htmlspecialchars(trim($_POST['password']));

    // 입력값 검증
    if (empty($student_id) || empty($password)) {
        echo "<script>alert('학번과 비밀번호를 입력해주세요.'); history.back();</script>";
        exit();
    }

    // 데이터베이스에서 사용자 정보 조회
    $sql = "SELECT * FROM REQUEST_METHOD WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':student_id' => $student_id]);
    $user = $stmt->fetch();

    // 사용자 정보 검증
    if ($user && password_verify($password, $user['password'])) {
        // 세션에 사용자 정보 저장
        $_SESSION['loggedin'] = true;
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        // notice_schedule.php로 리다이렉션
        header("Location: source/notice_schedule.php");
        exit();
    } else {
        // 로그인 실패 시 경고 메시지
        echo "<script>alert('학번 또는 비밀번호가 일치하지 않습니다.'); history.back();</script>";
        exit();
    }
} else {
    echo "잘못된 요청 방식입니다.";
    exit();
}
?>
