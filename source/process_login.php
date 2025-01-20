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
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
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

    // 필수 입력 값 확인
    if (empty($student_id) || empty($password)) {
        die("학번과 비밀번호를 모두 입력해주세요.");
    }

    // 학번 조건 추가 (예: 학번은 숫자로만 구성되어야 함)
    if (!ctype_digit($student_id)) {
        die("학번은 숫자로만 입력해야 합니다.");
    }

    // 비밀번호 조건 추가 (예: 최소 8자 이상이어야 함)
    if (strlen($password) < 8) {
        die("비밀번호는 최소 8자 이상이어야 합니다.");
    }

    // 데이터베이스에서 사용자 정보 조회
    $sql = "SELECT * FROM REQUEST_METHOD WHERE student_id = :student_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':student_id' => $student_id]);
    $user = $stmt->fetch();

    if ($user) {
        // 비밀번호 확인
        if (password_verify($password, $user['password'])) {
            // 조건이 모두 만족되면 세션 저장 및 페이지 이동
            $_SESSION['loggedin'] = true;
            $_SESSION['user_name'] = $user['name'];
            header("Location: notice_schedule.php");
            exit();
        } else {
            die("잘못된 비밀번호입니다.");
        }
    } else {
        die("등록되지 않은 학번입니다.");
    }
} else {
    echo "잘못된 요청 방식입니다.";
}
?>
