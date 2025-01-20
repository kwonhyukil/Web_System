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

// 데이터 조회
$sql = "SELECT * FROM NOTICES ORDER BY date DESC";
$stmt = $pdo->query($sql);
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
    </div>
  </body>
</html>
