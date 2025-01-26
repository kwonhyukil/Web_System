<?php
// 데이터베이스 연결 정보
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

// 수정할 공지사항 ID 가져오기
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    // URL에 id가 없거나 잘못된 값이면 목록 페이지로 리디렉션
    header('Location: notice.php');
    exit();
}

// 공지사항 데이터 가져오기
$sql = "SELECT title, content, author FROM notice_board WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('공지사항을 찾을 수 없습니다.');
}

// 공지사항 데이터를 가져와 $row에 저장
$row = $result->fetch_assoc();

// POST 요청으로 수정 데이터가 전달된 경우
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author = $_POST['author'] ?? '';

    // 데이터 검증
    if (empty($title) || empty($content) || empty($author)) {
        die('모든 필드를 입력해주세요.');
    }

    // 공지사항 수정
    $sql = "UPDATE notice_board SET title = ?, content = ?, author = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $title, $content, $author, $id);
    
    if ($stmt->execute()) {
        // 수정 완료 후 공지사항 페이지로 리디렉션
        header('Location: notice.php');
        exit();
    } else {
        echo "공지사항 수정 중 오류가 발생했습니다: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 수정</title>
</head>
<body>
    <h1>공지사항 수정</h1>
    <form action="" method="post">
        <!-- 공지사항 ID (숨김 필드) -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
        
        <label for="title">제목:</label><br>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($row['title']) ?>" required><br><br>
        
        <label for="content">내용:</label><br>
        <textarea id="content" name="content" rows="10" cols="50" required><?= htmlspecialchars($row['content']) ?></textarea><br><br>
        
        <label for="author">작성자:</label><br>
        <input type="text" id="author" name="author" value="<?= htmlspecialchars($row['author']) ?>" required><br><br>
        
        <button type="submit">수정 완료</button>
        <button type="button" onclick="location.href='notice.php'">뒤로가기</button>
    </form>
</body>
</html>
