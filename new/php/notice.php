<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.html");
    exit();
}

// 데이터베이스 연결 설정
$host = 'localhost';
$db = 'il_database';
$user = 'root';
$pass = 'gsc1234!@#$';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("데이터베이스 연결 실패: " . $e->getMessage());
}

// 페이지네이션 설정
$itemsPerPage = 5; // 페이지당 항목 수
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // 최소 페이지는 1
$offset = ($page - 1) * $itemsPerPage;

// 학년 필터 처리
$gradeFilter = isset($_GET['grade']) ? $_GET['grade'] : 'all';

// 데이터 조회 쿼리 설정
if ($gradeFilter === 'all') {
    $sqlCount = "SELECT COUNT(*) FROM NOTICES";
    $sql = "SELECT * FROM NOTICES ORDER BY date DESC LIMIT :offset, :itemsPerPage";
} else {
    $sqlCount = "SELECT COUNT(*) FROM NOTICES WHERE target = :grade";
    $sql = "SELECT * FROM NOTICES WHERE target = :grade ORDER BY date DESC LIMIT :offset, :itemsPerPage";
}

// 총 공지사항 수 계산
$stmt = $pdo->prepare($sqlCount);
if ($gradeFilter !== 'all') {
    $stmt->bindValue(':grade', $gradeFilter, PDO::PARAM_STR);
}
$stmt->execute();
$totalItems = $stmt->fetchColumn();

// 공지사항 데이터 가져오기
$stmt = $pdo->prepare($sql);
if ($gradeFilter !== 'all') {
    $stmt->bindValue(':grade', $gradeFilter, PDO::PARAM_STR);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);
$stmt->execute();
$notices = $stmt->fetchAll();

// 총 페이지 수 계산
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
                <td><?php echo htmlspecialchars($notice['title']); ?></td>
                <td><?php echo htmlspecialchars($notice['author']); ?></td>
                <td><?php echo htmlspecialchars($notice['date']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- 페이지네이션 -->
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
</div>
</body>
</html>
