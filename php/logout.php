<?php
session_start();
session_unset(); // 모든 세션 변수 제거
session_destroy(); // 세션 종료

// 로그인 페이지로 리다이렉션
header("Location: login.html");
exit();
?>
