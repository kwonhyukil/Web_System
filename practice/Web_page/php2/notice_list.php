<?php
// 데이터베이스 연결
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

// 공지사항 ID 가져오기
$id = $_GET['id'] ?? 0;
$sql = "SELECT * FROM notice_board WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die('공지사항을 찾을 수 없습니다.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 세부내용</title>
</head>
<body>
    <h2>공지사항 세부내용</h2>
    <p><strong>제목:</strong> <?= htmlspecialchars($row['title']) ?></p>
    <p><strong>작성자:</strong> <?= htmlspecialchars($row['author']) ?></p>
    <p><strong>작성 시간:</strong> <?= $row['create_at'] ?></p>
    <p><strong>내용:</strong> <?= nl2br(htmlspecialchars($row['content'])) ?></p>
    <button onclick="location.href = 'notice_board.php'">목록으로</button>
  <button onclick = "location.href = 'notice_writing.php'">공지사항 작성</button>
  <button onclick = "location.href = '../html2/main_menu.html'">뒤로가기</button>  
</body>
</html>

<?php
$conn->close();
?>
