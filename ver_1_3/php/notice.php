<?php
session_start();

require_once 'db_connect.php';  // 데이터베이스 연결 함수 포함

// 현재 로그인 사용자의 권한 가져오기 (로그인된 사용자가 없을 경우 기본값 'student')
$user_role = $_SESSION['role'] ?? 'student';

// 대상 학년 필터 및 검색어 필터 처리
$selected_grade = $_GET['grade'] ?? 'all';  // 학년 선택 값 ('all'이 기본값)
$search_keyword = $_GET['keyword'] ?? '';    // 검색어 (기본값은 빈 문자열)

// 현재 페이지 번호 확인 (기본값은 1페이지)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;  // 페이지 번호가 1보다 작으면 1로 설정

// 페이지당 표시할 공지사항 수
$notices_per_page = 10;

// 데이터베이스 연결
$conn = connectDatabase();

// **1. 총 공지사항 수 조회**
$total_count_sql = "SELECT COUNT(*) AS total_count FROM notices WHERE 1=1";  // 조건이 없는 기본 쿼리

$params = [];  // 파라미터 배열
$types = '';   // 바인딩 타입 문자열

// 대상 학년 필터 조건 추가
if ($selected_grade !== 'all') {
    $total_count_sql .= " AND target_grade = ?";
    $params[] = $selected_grade;  // 파라미터 배열에 학년 추가
    $types .= 's';                // 문자열 타입 추가
}

// 검색어 조건 추가
if (!empty($search_keyword)) {
    $total_count_sql .= " AND title LIKE ?";
    $params[] = '%' . $search_keyword . '%';  // 검색어에 '%'를 붙여 LIKE 조건 사용
    $types .= 's';                             // 문자열 타입 추가
}

// **2. 쿼리 실행 (총 개수 조회)**
$count_stmt = $conn->prepare($total_count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);  // 조건이 있으면 파라미터 바인딩
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_count = $count_result->fetch_assoc()['total_count'];  // 총 공지사항 수 가져오기

// 총 페이지 수 계산
$total_pages = ceil($total_count / $notices_per_page);

// 페이지네이션을 위한 시작 위치(offset) 계산
$offset = ($page - 1) * $notices_per_page;

// **3. 공지사항 목록 조회 쿼리 작성**
$sql = "SELECT notice_id, title, author, target_grade, created_at FROM notices WHERE 1=1";

// 대상 학년 필터 조건 추가
if ($selected_grade !== 'all') {
    $sql .= " AND target_grade = ?";
}

// 검색어 조건 추가
if (!empty($search_keyword)) {
    $sql .= " AND title LIKE ?";
}

// 정렬 및 페이지네이션 설정 (최신순 정렬)
$sql .= " ORDER BY created_at DESC LIMIT ?, ?";

// 쿼리 파라미터에 offset과 limit 값 추가
$params[] = $offset;
$params[] = $notices_per_page;
$types .= 'ii';  // offset과 limit은 정수형

// 쿼리 실행
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);  // 모든 조건에 대한 파라미터 바인딩
$stmt->execute();
$result = $stmt->get_result();  // 결과 가져오기
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>공지사항</title>
</head>
<body>
    <h2>공지사항</h2>

    <!-- 검색 및 대상 학년 선택 폼 -->
    <form action="notice.php" method="get">
        <label for="grade">대상 학년:</label>
        <select id="grade" name="grade" onchange="this.form.submit()">
            <option value="all" <?= $selected_grade === 'all' ? 'selected' : '' ?>>전체</option>
            <option value="1학년" <?= $selected_grade === '1학년' ? 'selected' : '' ?>>1학년</option>
            <option value="2학년" <?= $selected_grade === '2학년' ? 'selected' : '' ?>>2학년</option>
            <option value="3학년" <?= $selected_grade === '3학년' ? 'selected' : '' ?>>3학년</option>
        </select>

        <label for="keyword">검색:</label>
        <input type="text" id="keyword" name="keyword" value="<?= htmlspecialchars($search_keyword) ?>">  <!-- 검색어 입력 -->
        <button type="submit">검색</button>
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
            <th>삭제</th>  <!-- 작업 열 -->
        </tr>
        <?php
        if ($result->num_rows > 0) {
            $counter = $offset + 1;  // 공지사항 번호 계산
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$counter}</td>
                        <td><a href='notice_detail.php?id={$row['notice_id']}'>" . htmlspecialchars($row['title']) . "</a></td>
                        <td>" . htmlspecialchars($row['author']) . "</td>
                        <td>" . htmlspecialchars($row['target_grade']) . "</td>
                        <td>{$row['created_at']}</td>
                        <td>";

                // 관리자만 삭제 버튼 표시
                if ($user_role === 'admin') {
                    echo "
                        <form action='notice_delete.php' method='post' style='display:inline;'>
                            <input type='hidden' name='notice_id' value='{$row['notice_id']}'>
                            <button type='submit' onclick=\"return confirm('정말 삭제하시겠습니까?')\">삭제</button>
                        </form>
                    ";
                }

                echo "</td></tr>";
                $counter++;
            }
        } else {
            echo "<tr><td colspan='6'>해당 조건에 맞는 공지사항이 없습니다.</td></tr>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </table>

    <br>

    <!-- 페이지네이션 -->
    <div>
        <?php if ($page > 1): ?>
            <a href="notice.php?grade=<?= $selected_grade ?>&keyword=<?= urlencode($search_keyword) ?>&page=<?= $page - 1 ?>">이전</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="notice.php?grade=<?= $selected_grade ?>&keyword=<?= urlencode($search_keyword) ?>&page=<?= $i ?>" <?= $i === $page ? 'style="font-weight:bold;"' : '' ?>>
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="notice.php?grade=<?= $selected_grade ?>&keyword=<?= urlencode($search_keyword) ?>&page=<?= $page + 1 ?>">다음</a>
        <?php endif; ?>
    </div>

    <br>
    <button type="button" onclick="location.href='../html/notice_write.html'">작성하기</button>
    <button type="button" onclick="location.href='../html/main_page.html'">뒤로가기</button>
</body>
</html>
