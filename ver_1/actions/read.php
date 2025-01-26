<?php
// 데이터베이스 연결 정보
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

// 데이터베이스 연결
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

// 공지사항 ID 가져오기
$id = $_GET['id'] ?? null;

if (!$id) {
    die('공지사항 ID가 필요합니다.');
}

// 공지사항 데이터 가져오기
$sql = "SELECT title, content, author, create_at FROM notice_board WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('공지사항을 찾을 수 없습니다.');
}

$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 상세보기</title>
</head>
<body>
    <h1><?= htmlspecialchars($row['title']) ?></h1>
    <p><strong>작성자:</strong> <?= htmlspecialchars($row['author']) ?></p>
    <p><strong>작성일:</strong> <?= htmlspecialchars($row['create_at']) ?></p>
    <hr>
    <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
    <button onclick="location.href='notice.php'">목록으로 돌아가기</button>
    <button onclick="location.href='update.php?id=<?= $id ?>'">수정</button>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
