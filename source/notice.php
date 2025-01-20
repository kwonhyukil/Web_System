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
    <title>공지사항</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f0f8ff;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }

      .container {
        background: white;
        border-radius: 10px;
        padding: 20px;
        width: 360px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        text-align: center;
      }

      .header {
        font-size: 20px;
        font-weight: bold;
        background-color: #007bff;
        color: white;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 20px;
      }

      .dropdown {
        margin-bottom: 20px;
      }

      .dropdown select {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 5px;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 14px;
      }

      table th,
      table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
      }

      table th {
        background-color: #f4f4f4;
        font-weight: bold;
      }

      .pagination {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
      }

      .pagination a {
        margin: 0 5px;
        padding: 5px 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-decoration: none;
        color: #007bff;
      }

      .pagination a:hover {
        background-color: #007bff;
        color: white;
      }

      .back-button {
        background-color: #ffc107;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
      }

      .back-button:hover {
        background-color: #e0a800;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <!-- 헤더 -->
      <div class="header">공지사항</div>

      <!-- 드롭다운 -->
      <div class="dropdown">
        <select id="grade-filter">
          <option value="all">전체</option>
          <option value="1">1학년</option>
          <option value="2">2학년</option>
          <option value="3">3학년</option>
        </select>
      </div>

      <!-- 공지사항 테이블 -->
      <table>
        <thead>
          <tr>
            <th>번호</th>
            <th>제목</th>
            <th>작성일</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>학과 프로그램 사용법</td>
            <td>2024.01.11</td>
          </tr>
          <tr>
            <td>2</td>
            <td>학과 프로그램 사용법</td>
            <td>2024.01.11</td>
          </tr>
          <tr>
            <td>3</td>
            <td>학과 프로그램 사용법</td>
            <td>2024.01.11</td>
          </tr>
          <!-- 데이터가 더 있다면 추가 -->
        </tbody>
      </table>

      <!-- 페이지네이션 -->
      <div class="pagination">
        <a href="#">1</a>
        <a href="#">2</a>
        <a href="#">3</a>
        <a href="#">4</a>
        <a href="#">5</a>
        <a href="#">6</a>
      </div>

      <!-- 돌아가기 버튼 -->
      <button class="back-button" onclick="location.href='notice_schedule.php'">
        돌아가기
      </button>
    </div>
  </body>
</html>
