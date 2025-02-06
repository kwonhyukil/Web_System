<?php

require_once 'session_manager.php';

// 페이지 내용 작성
echo "하이, ", ($_SESSION['name']) . "님 ㅎ";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>메인 화면</title>
</head>
<body>

    <h2>메인 화면<h2>

    <button type="button" onclick="location.href='notice.php'">공지사항</button>
    
</body>
</html>