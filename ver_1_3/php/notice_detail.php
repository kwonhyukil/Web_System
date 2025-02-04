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

// **1. 공지사항 정보 조회**
$sql = "SELECT title, content, author, created_at FROM notices WHERE notice_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $notice_id);
$stmt->execute();
$notice_result = $stmt->get_result();

// **2. 댓글 및 대댓글 조회 (작성 시간 순서대로 정렬)**
$comment_stmt = $conn->prepare("
    SELECT * FROM comments 
    WHERE notice_id = ? 
    ORDER BY parent_id ASC, created_at ASC
");
$comment_stmt->bind_param('i', $notice_id);
$comment_stmt->execute();
$comments = $comment_stmt->get_result();

// 댓글을 계층 구조로 변환
$comment_tree = [];
while ($comment = $comments->fetch_assoc()) {
    if ($comment['parent_id'] === null) {
        // 기본 댓글
        $comment_tree[$comment['comment_id']] = $comment;
        $comment_tree[$comment['comment_id']]['replies'] = [];
    } else {
        // 대댓글
        $comment_tree[$comment['parent_id']]['replies'][] = $comment;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 상세보기</title>
    <style>
        .reply-form {
            display: none;
            margin-top: 10px;
        }
    </style>
    <script>
        function toggleReplyForm(commentId) {
            const form = document.getElementById('reply-form-' + commentId);
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <?php
    if ($notice_result->num_rows > 0) {
        // 공지사항 데이터 출력
        $notice = $notice_result->fetch_assoc();
        echo "<h1>" . htmlspecialchars($notice['title']) . "</h1>";
        echo "<p>작성자: " . htmlspecialchars($notice['author']) . "</p>";
        echo "<p>작성일: {$notice['created_at']}</p>";
        echo "<p>" . nl2br(htmlspecialchars($notice['content'])) . "</p>";
    } else {
        echo "<p>해당 공지사항이 존재하지 않습니다.</p>";
    }

    $stmt->close();
    ?>

    <hr>

    <!-- 댓글 및 대댓글 출력 -->
    <h3>댓글 목록</h3>
    <?php if (!empty($comment_tree)): ?>
        <ul>
            <?php foreach ($comment_tree as $comment): ?>
                <li>
                    <strong><?= htmlspecialchars($comment['author']) ?></strong> (<?= $comment['created_at'] ?>)
                    <button type="button" onclick="toggleReplyForm(<?= $comment['comment_id'] ?>)">대댓글 작성</button>
                    <?= nl2br(htmlspecialchars($comment['content'])) ?>
                    <!-- 대댓글 작성 버튼 -->
                   

                    <!-- 대댓글 작성 폼 -->
                    <div id="reply-form-<?= $comment['comment_id'] ?>" class="reply-form">
                        <form action="comment_write.php" method="post">
                            <input type="hidden" name="notice_id" value="<?= $notice_id ?>">
                            <input type="hidden" name="parent_id" value="<?= $comment['comment_id'] ?>">
                            <label for="reply_author_<?= $comment['comment_id'] ?>">작성자:</label>
                            <input type="text" id="reply_author_<?= $comment['comment_id'] ?>" name="author" required>
                            <br><br>
                            <label for="reply_content_<?= $comment['comment_id'] ?>">내용:</label>
                            <textarea id="reply_content_<?= $comment['comment_id'] ?>" name="content" rows="2" required></textarea>
                            <br>
                            <button type="submit">대댓글 작성</button>
                        </form>
                    </div>

                    <hr>

                    <!-- 대댓글 출력 -->
                    <?php if (!empty($comment['replies'])): ?>
                        <ul style="margin-left: 20px;">
                            <?php foreach ($comment['replies'] as $reply): ?>
                                <li>
                                    <strong><?= htmlspecialchars($reply['author']) ?></strong> (<?= $reply['created_at'] ?>)<br>
                                    <?= nl2br(htmlspecialchars($reply['content'])) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>댓글이 없습니다.</p>
    <?php endif; ?>

    <?php
    $comment_stmt->close();
    ?>

    <hr>

    <!-- 댓글 작성 폼 -->
    <h3>댓글 작성</h3>
    <form action="comment_write.php" method="post">
        <input type="hidden" name="notice_id" value="<?= $notice_id ?>">
        <label for="author">작성자:</label>
        <input type="text" id="author" name="author" required>
        <br><br>
        <label for="content">댓글 내용:</label>
        <textarea id="content" name="content" rows="4" required></textarea>
        <br><br>
        <button type="submit">댓글 작성</button>
    </form>

    <br>

    <!-- 관리자 권한일 경우에만 수정 버튼 표시 -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <button type="button" onclick="location.href='notice_edit.php?id=<?= $notice_id ?>'">수정하기</button>
    <?php endif; ?>

    <button type="button" onclick="location.href='notice.php'">목록으로 돌아가기</button>
</body>
</html>
