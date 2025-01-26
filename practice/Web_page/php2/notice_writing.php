<?php
session_start(); // 세션 시작

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

// 데이터베이스 연결
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author = $_POST['author'] ?? '';

    // 필드 확인
    if (empty($title) || empty($content) || empty($author)) {
        die('모든 필드를 입력해주세요!');
    }

    // 데이터 삽입
    $sql = "INSERT INTO notice_board (title, content, author, create_at) VALUES (?, ?, ?, current_timestamp())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $title, $content, $author);

    if ($stmt->execute()) {
        echo "공지사항이 성공적으로 작성되었습니다.";
        header('location: ../html2/notice.html');
    } else {
        echo "작성 중 오류 발생: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>
