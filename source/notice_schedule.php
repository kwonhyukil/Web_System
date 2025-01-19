<?php
session_start();

// 로그인 상태 확인
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.html");
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
      /* 전체 페이지 스타일 */
      body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
      }

      .container {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 400px;
        text-align: center;
      }

      .logo img {
        width: 100px;
        margin-bottom: 15px;
      }

      .logo h2 {
        font-size: 20px;
        color: #333;
        margin-bottom: 20px;
      }

      /* 버튼 스타일 */
      .button-group {
        display: flex;
        flex-direction: column;
        gap: 20px;
      }

      .button {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        color: white;
        transition: background-color 0.3s;
      }

      .notice-button {
        background-color: #007bff;
      }

      .notice-button:hover {
        background-color: #0056b3;
      }

      .schedule-button {
        background-color: #28a745;
      }

      .schedule-button:hover {
        background-color: #218838;
      }
    </style>
  </head>
  <body>
    <button class="logout-button" onclick="location.href='../index.html'">
      로그아웃
    </button>
   </div>

      <!-- 버튼 그룹 -->
      <div class="button-group">
        <button
          class="button notice-button"
          onclick="location.href='notice.php'"
        >
          공지사항 확인
        </button>
        <button
          class="button schedule-button"
          onclick="location.href='schedule.php'"
        >
          시간표 확인
        </button>
      </div>
    </div>
  </body>
</html>
