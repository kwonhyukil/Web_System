<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.html");
    exit();
}

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

// 공지사항 ID 확인
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("잘못된 요청입니다.");
}

$id = (int)$_GET['id'];

// 공지사항 데이터 가져오기
$sql = "SELECT * FROM NOTICES WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$notice = $stmt->fetch();

if (!$notice) {
    die("공지사항을 찾을 수 없습니다.");
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($notice['title']); ?></title>
    <link rel="stylesheet" href="../css/view_notice.css">
</head>
<body>
<div class="container">
    <div class="header">
        <?php echo htmlspecialchars($notice['title']); ?>
        <button class="back-button" onclick="location.href='notice.php'">뒤로가기</button>
    </div>
    <div class="notice-details">
        <p><strong>작성자:</strong> <?php echo htmlspecialchars($notice['author']); ?></p>
        <p><strong>대상 학년:</strong> <?php echo htmlspecialchars($notice['target']); ?></p>
        <p><strong>작성일:</strong> <?php echo htmlspecialchars($notice['date']); ?></p>
        <hr>
        <p><?php echo nl2br(htmlspecialchars($notice['content'])); ?></p>
    </div>
</div>
</body>
</html>
