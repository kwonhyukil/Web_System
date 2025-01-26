<?php
session_start();

// 데이터베이스 연결
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

// POST 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 입력값 가져오기
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author = $_POST['author'] ?? '';

    // 입력값 유효성 검사
    if (empty($title) || empty($content) || empty($author)) {
        die('모든 필드를 입력해주세요!');
    }

    // SQL 쿼리 준비 및 실행
    $sql = "INSERT INTO notice_board (title, content, author, create_at) VALUES (?, ?, ?, current_timestamp())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $title, $content, $author);

    if ($stmt->execute()) {
        // 성공 시 알림 및 리디렉션
        $_SESSION['message'] = '공지사항이 성공적으로 작성되었습니다.';
        header('Location: notice.php');
        exit(); // 추가 출력 방지
    } else {
        echo "작성 중 오류 발생: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
