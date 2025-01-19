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

// 로그인 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = htmlspecialchars(trim($_POST['student_id']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (empty($student_id) || empty($password)) {
        die("학번과 비밀번호를 입력해주세요.");
    }

    // 데이터베이스에서 사용자 조회
    $sql = "SELECT * FROM REQUEST_METHOD WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':student_id' => $student_id]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // 로그인 성공 -> 세션 저장
            $_SESSION['loggedin'] = true;
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // 공지사항 및 시간표 페이지로 이동
            header("Location: notice_schedule.html");
            exit();
        } else {
            echo "<script>alert('잘못된 비밀번호입니다.'); history.back();</script>";
        }
    } else {
        echo "<script>alert('학번이 존재하지 않습니다.'); history.back();</script>";
    }
} else {
    echo "잘못된 요청 방식입니다.";
}
?>
