<?php
// 데이터베이스 연결 정보
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

// 삭제할 공지사항 ID 가져오기
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    die('유효하지 않은 공지사항 ID입니다.');
}

// 공지사항 삭제 쿼리
$sql = "DELETE FROM notice_board WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    // 삭제 성공 시 공지사항 목록 페이지로 리디렉션
    header('Location: notice.php');
    exit();
} else {
    echo "공지사항 삭제 중 오류 발생: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
