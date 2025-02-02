<?php
session_start();

require_once 'db_connect.php';

// URL에서 공지사항 ID 가져오기
$notice_id = $_GET['id'] ?? null;

if (!$notice_id) {
    header("Location: notice.php");
    exit;
}

// 데이터베이스 연결
$conn = connectDatabase();

// SQL 쿼리 작성 및 실행
$sql = "SELECT title, content, author, created_at FROM notices WHERE notice_id = ?";
$stmt = $conn->prepare($sql);
$stmt -> bind_param('i', $notice_id);
$stmt -> execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 상세보기</title>
</head>
<body>
    <?php
    if ($result->num_rows > 0){
        // 공지사항 데이터를 가져와서 변수에 저장
        $notice = $result->fetch_assoc();

        // 공지사항 정보 출력
        echo "<h1>{$notice['title']}</h1>";
        echo "<p>작성자: {$notice['author']}</p>";
        echo "<p>작성일: {$notice['created_at']}</p>";
        echo "<p>{$notice['content']}</p>";
    } else {
        echo "<p>해당 공지사항이 존재하지 않습니다.</p>";
    }
    
    $stmt->close();
    $conn->close();
    ?>

    <!-- 관리자 권한일 경우에만 수정 버튼 표시 -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <button type="button" onclick="location.href='notice_edit.php?id=<?= $notice_id ?>'">수정하기</button>
    <?php endif; ?>

    <button type="button" onclick="location.href='notice.php'">목록으로 돌아가기</button>
</body>
    
</body>
</html>