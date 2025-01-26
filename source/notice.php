<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.html");
    exit();
}

// 데이터베이스 연결
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

// 현재 페이지 번호 가져오기 (기본값: 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // 최소 페이지는 1

// 한 페이지에 표시할 데이터 개수
$itemsPerPage = 5;

// 데이터베이스에서 시작 위치 계산
$offset = ($page - 1) * $itemsPerPage;

// 전체 데이터 개수 가져오기
$totalItemsQuery = $pdo->query("SELECT COUNT(*) AS count FROM NOTICES");
$totalItems = $totalItemsQuery->fetch()['count'];

// 전체 페이지 수 계산
$totalPages = ceil($totalItems / $itemsPerPage);

// 현재 페이지에 표시할 데이터 가져오기
$sql = "SELECT * FROM NOTICES ORDER BY date DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$notices = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>공지사항</title>
    <link rel="stylesheet" href="../css/notice.css" />
  </head>
  <body>
    <div class="container">
      <div class="header">
        공지사항
        <button class="write-button" onclick="location.href='write_notice.php'">
          작성하기
        </button>
      </div>

      <!-- 공지사항 테이블 -->
      <table>
        <thead>
          <tr>
            <th>번호</th>
            <th>제목</th>
            <th>작성자</th>
            <th>작성일</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($notices as $notice): ?>
            <tr>
              <td><?php echo htmlspecialchars($notice['id']); ?></td>
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
          <a href="?page=<?php echo $page - 1; ?>">이전</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="?page=<?php echo $i; ?>" <?php if ($i === $page) echo 'class="active"'; ?>>
            <?php echo $i; ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="?page=<?php echo $page + 1; ?>">다음</a>
        <?php endif; ?>
      </div>

      <!-- 돌아가기 버튼 -->
      <button class="back-button" onclick="location.href='notice_schedule.php'">
        돌아가기
      </button>
    </div>
  </body>
</html>
