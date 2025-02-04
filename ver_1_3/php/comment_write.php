<?php
session_start();
require_once 'db_connect.php';

// 폼 데이터 가져오기
$notice_id = $_POST['notice_id'] ?? null;
$parent_id = $_POST['parent_id'] ?? null;
$author = $_POST['author'] ?? '';
$content = $_POST['content'] ?? '';

if (!$notice_id || empty($author) || empty($content)) {
    echo "<script>alert('모든 필드를 입력해 주세요.'); history.back();</script>";
    exit;
}

// 데이터베이스 연결
$conn = connectDatabase();

// 댓글 삽입 쿼리
$comment_stmt = $conn->prepare("INSERT INTO comments (notice_id, parent_id, author, content) VALUES (?, ?, ?, ?)");
$comment_stmt->bind_param('iiss', $notice_id, $parent_id, $author, $content);

if ($comment_stmt->execute()) {
    echo "<script>alert('댓글이 작성되었습니다.'); location.href='notice_detail.php?id=$notice_id';</script>";
} else {
    echo "댓글 작성 중 오류가 발생했습니다: " . $comment_stmt->error;
}

$comment_stmt->close();
$conn->close();
?>
