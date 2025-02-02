<?php
session_start();

require_once 'db_connect.php';

// 대상 학년 필터 처리
$selected_grade = $_GET['grade'] ?? 'all';

// 데이터베이스 연결
$conn = connectDatabase();

// SQL 쿼리 작성
if ($selected_grade === 'all') {
    $sql = "SELECT notice_id, title, author, target_grade, created_at FROM notices";
} else {
    $sql = "SELECT notice_id, title, author, target_grade, created_at FROM notices WHERE target_grade = ?";
}

$stmt = $conn->prepare($sql);
if ($selected_grade !== 'all') {
    $stmt->bind_param('s', $selected_grade);
}

$stmt->execute();
$result = $stmt->get_result();

// 현재 로그인 사용자의 권한 가져오기
$user_role = $_SESSION['role'] ?? 'student';  // 기본값은 'student'
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>공지사항</title>
</head>
<body>
    <h2>공지사항</h2>

    <!-- 대상 학년 선택 폼 -->
    <form action="notice.php" method="get">
        <label for="grade">대상 학년:</label>
        <select id="grade" name="grade" onchange="this.form.submit()">
            <option value="all" <?= $selected_grade === 'all' ? 'selected' : '' ?>>전체</option>
            <option value="1_grade" <?= $selected_grade === '1_grade' ? 'selected' : '' ?>>1학년</option>
            <option value="2_grade" <?= $selected_grade === '2_grade' ? 'selected' : '' ?>>2학년</option>
            <option value="3_grade" <?= $selected_grade === '3_grade' ? 'selected' : '' ?>>3학년</option>
        </select>
    </form>

    <br>

    <!-- 공지사항 목록 테이블 -->
    <table border="1">
        <tr>
            <th>번호</th>
            <th>제목</th>
            <th>작성자</th>
            <th>대상 학년</th>
            <th>등록일</th>
            <th>작업</th>  <!-- 작업 열 -->
        </tr>
        <?php
        // 공지사항 출력
        if ($result->num_rows > 0) {
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$counter}</td>
                        <td><a href='notice_detail.php?id={$row['notice_id']}'>{$row['title']}</a></td>
                        <td>{$row['author']}</td>
                        <td>{$row['target_grade']}</td>
                        <td>{$row['created_at']}</td>
                        <td>";

                // 관리자만 삭제 및 수정 버튼 표시
                if ($user_role === 'admin') {
                    echo "
                        <form action='notice.php' method='post' style='display:inline;'>
                            <input type='hidden' name='notice_id' value='{$row['notice_id']}'>
                            <button type='submit' onclick=\"return confirm('정말 삭제하시겠습니까?')\">삭제</button>
                        </form>

                    ";
                }

                echo "</td></tr>";
                $counter++;
            }
        } else {
            echo "<tr><td colspan='6'>해당 학년의 공지사항이 없습니다.</td></tr>";
        }

        // 데이터베이스 연결 종료
        $stmt->close();
        $conn->close();
        ?>
    </table>

    <br>
    <button type="button" onclick="location.href='../html/notice_write.html'">작성하기</button>
    <button type="button" onclick="location.href='../html/main_page.html'">뒤로가기</button>
</body>
</html>
