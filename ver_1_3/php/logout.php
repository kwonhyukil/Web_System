<?php
session_start();  // 세션 시작

// 모든 세션 변수 제거
session_unset();

// 세션 완전 종료
session_destroy();

// 로그아웃 후 메인 페이지로 리다이렉트
header("Location: ../html/login.html");
exit;
?>
