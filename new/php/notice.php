<?php
// 데이터베이스 연결 설정
$host = 'localhost';
$dbname = 'your_database_name';
$user = 'your_username';
$password = 'your_password';

try {
    // PDO를 이용한 데이터베이스 연결
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// 학년 필터 설정 (GET 파라미터로 받음)
$gradeFilter = isset($_GET['grade']) ? $_GET['grade'] : 'all';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 5; // 한 페이지에 표시할 항목 수
$offset = ($page - 1) * $itemsPerPage;

// 기본 SQL 쿼리
$sql = "SELECT id, notice_name, title, date, target, author FROM your_table_name";
if ($gradeFilter !== 'all') {
    $sql .= " WHERE target = :grade";
}
$sql .= " ORDER BY date DESC LIMIT :offset, :itemsPerPage";

$stmt = $pdo->prepare($sql);

// 파라미터 바인딩
if ($gradeFilter !== 'all') {
    $stmt->bindParam(':grade', $gradeFilter, PDO::PARAM_INT);
}
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);

// 쿼리 실행
$stmt->execute();
$notices = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 총 공지사항 개수 계산
$countSql = "SELECT COUNT(*) FROM your_table_name";
if ($gradeFilter !== 'all') {
    $countSql .= " WHERE target = :grade";
}
$countStmt = $pdo->prepare($countSql);
if ($gradeFilter !== 'all') {
    $countStmt->bindParam(':grade', $gradeFilter, PDO::PARAM_INT);
}
$countStmt->execute();
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항</title>
    <link rel="stylesheet" href="../css/notice.css">
</head>
<body>
<div class="container">
    <div class="header">
        공지사항
        <button class="write-button" onclick="location.href='../html/write_notice.html'">작성하기</button>
    </div>

    <!-- 학년 필터 -->
    <div class="dropdown">
        <form method="GET" action="notice.php">
            <select name="grade" onchange="this.form.submit()">
                <option value="all" <?php if ($gradeFilter === 'all') echo 'selected'; ?>>전체</option>
                <option value="1" <?php if ($gradeFilter === '1') echo 'selected'; ?>>1학년</option>
                <option value="2" <?php if ($gradeFilter === '2') echo 'selected'; ?>>2학년</option>
                <option value="3" <?php if ($gradeFilter === '3') echo 'selected'; ?>>3학년</option>
            </select>
        </form>
    </div>

    <!-- 공지사항 테이블 -->
    <table>
        <thead>
        <tr>
            <th>번호</th>
            <th>학년</th>
            <th>제목</th>
            <th>작성자</th>
            <th>작성일</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $itemNumber = $offset + 1; // 번호 초기화
        foreach ($notices as $notice): ?>
            <tr>
                <td><?php echo $itemNumber++; ?></td>
                <td><?php echo htmlspecialchars($notice['target']); ?></td>
                <td>
                    <a href="view_notice.php?id=<?php echo $notice['id']; ?>" class="notice-title-link">
                        <?php echo htmlspecialchars($notice['title']); ?>
                    </a>
                </td>
                <td><?php echo htmlspecialchars($notice['author']); ?></td>
                <td><?php echo htmlspecialchars($notice['date']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- 페이지네이션 -->
    <div class="pagination-container">
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?grade=<?php echo $gradeFilter; ?>&page=<?php echo $page - 1; ?>">이전</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?grade=<?php echo $gradeFilter; ?>&page=<?php echo $i; ?>" 
                   <?php if ($i === $page) echo 'style="font-weight: bold; text-decoration: underline;"'; ?>>
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?grade=<?php echo $gradeFilter; ?>&page=<?php echo $page + 1; ?>">다음</a>
            <?php endif; ?>
        </div>
        <button class="back-button" onclick="location.href='../html/notice_schedule.html'">뒤로가기</button>
    </div>
</div>
</body>
</html>
