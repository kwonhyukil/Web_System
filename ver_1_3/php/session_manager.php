<?php

session_start();

// 로그인 상태 확인
if (!insset($_SESSION['user_id'])) {
    // 로그인 상태가 아니면 로그인 페이지로 리다이렉트
    header("Location: login.php");
    exit;
}