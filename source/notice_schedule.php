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
        position: relative;
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

      /* 로그아웃 버튼 스타일 */
      .logout-button {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 10px 15px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
      }

      /* 버튼 스타일 */
      .button {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        color: white;
        transition: background-color 0.3s;
        text-align: center;
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
    <div class="container">
      <!-- 로그아웃 버튼 -->
      <button class="logout-button" onclick="location.href='../index.html'">
        로그아웃
      </button>

      <!-- 로고 -->
      <div class="logo">
        <img src="../image/yeungjin_logo.jfif" alt="Yeungjin University Logo" />
        <h2>영진전문대학교</h2>
      </div>

      <!-- 공지사항 버튼 -->
      <button class="button notice-button" onclick="location.href='notice.php'">
        공지사항 확인
      </button>

      <!-- 시간표 버튼 -->
      <button
        class="button schedule-button"
        onclick="location.href='schedule.php'"
      >
        시간표 확인
      </button>
    </div>
  </body>
</html>
