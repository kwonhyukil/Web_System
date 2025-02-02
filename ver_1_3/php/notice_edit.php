<?php

session_start();

require_once 'db_connect.php';

// URL 파라미터로 전달된 공지사항 ID 가져오기
$notice_id = $_GET['id'] ?? null;

if (!$notice_id) {
    header("Location: notice.php");  // 목록 페이지로 이동
    exit;
}

// 데이터베이스 연결
$conn = connectDatabase();

// SQL 쿼리 작성 및 실행
// 오타 수정: target_garde -> target_grade
$sql = "SELECT title, content, author, target_grade FROM notices WHERE notice_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $notice_id);
$stmt->execute();

// 결과 가져오기
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "해당 공지사항이 존재하지 않습니다.";
    exit;
}

// 공지사항 데이터 가져오기
$notice = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>공지사항 수정</title>
</head>
<body>
    <h2>공지사항 수정</h2>

    <!-- 수정 폼 시작 -->
    <form action="notice_update.php" method="post">

        <!-- 공지사항 ID를 hidden input으로 전달 -->
        <input type="hidden" name="notice_id" value="<?= $notice_id ?>">

        <!-- 제목 입력 필드 (기존 값 출력) -->
        <label for="title">제목:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($notice['title']) ?>" required>
        <br><br>

        <!-- 내용 입력 필드 (기존 값 출력) -->
        <label for="content">내용:</label>
        <textarea id="content" name="content" required><?= htmlspecialchars($notice['content']) ?></textarea>
        <br><br>

        <!-- 대상 학년 선택 필드 (기존 값에 따라 선택 표시) -->
        <label for="target_grade">대상 학년:</label>
        <select id="target_grade" name="target_grade" required>
            <option value="1_grade" <?= $notice['target_grade'] === '1_grade' ? 'selected' : '' ?>>1학년</option>
            <option value="2_grade" <?= $notice['target_grade'] === '2_grade' ? 'selected' : '' ?>>2학년</option>
            <option value="3_grade" <?= $notice['target_grade'] === '3_grade' ? 'selected' : '' ?>>3학년</option>
        </select>
        <br><br>

        <!-- 제출 버튼 -->
        <button type="submit">수정하기</button>
    </form>
</body>
</html>