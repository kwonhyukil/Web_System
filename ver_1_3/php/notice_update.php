<?php
// 세션 시작
session_start();

// 데이터베이스 연결 파일 불러오기
require_once 'db_connect.php';

// 폼에서 전송된 데이터 가져오기
$notice_id = $_POST['notice_id'] ?? null;
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$target_grade = $_POST['target_grade'] ?? '';

// 필수 데이터 검증 (공지사항 ID, 제목, 내용이 없는 경우 에러 처리)
if (!$notice_id || empty($title) || empty($content)) {
    echo "잘못된 요청입니다.";
    exit;
}

// 데이터베이스 연결
$conn = connectDatabase();

// 공지사항 수정 쿼리 작성
$sql = "UPDATE notices SET title = ?, content = ?, target_grade = ? WHERE notice_id = ?";
$stmt = $conn->prepare($sql);

// 수정할 데이터 바인딩 (문자열 3개, 정수 1개)
$stmt->bind_param('sssi', $title, $content, $target_grade, $notice_id);

// 쿼리 실행 및 결과 확인
if ($stmt->execute()) {
    // 수정 성공 시 목록 페이지로 리다이렉트
    header("Location: notice.php");
    exit;
} else {
    // 수정 실패 시 에러 메시지 출력
    echo "공지사항 수정 중 오류가 발생했습니다: " . $stmt->error;
}

// 연결 종료
$stmt->close();
$conn->close();
?>