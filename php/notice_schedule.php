<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>공지사항 및 시간표</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
      }
      .logout-button {
        position: absolute;
        top: 20px;
        right: 20px;
        background-color: #dc3545;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
      }
    </style>
  </head>
  <body>
    <button class="logout-button" onclick="location.href='logout.php'">
      로그아웃
    </button>
    <div class="container">
      <h1>안녕하세요, <?php echo htmlspecialchars($_SESSION['user_name']); ?>님!</h1>
      <p>공지사항 및 시간표를 확인하세요.</p>
    </div>
  </body>
</html>
