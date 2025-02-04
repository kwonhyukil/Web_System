<?php
session_start();

require_once 'db_connect.php';

// 로그인 사용자의 권한 확인
$user_role = $_SESSION['role'] ?? 'student';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notice_id']) && $user_role === 'admin') {
    // 데이터베이스 연결
    $conn = connectDatabase();

    // 삭제할 공지사항 ID 가져오기
    $notice_id = $_POST['notice_id'];

    // SQL 삭제 쿼리 작성 및 실행
    $delete_sql = "DELETE FROM notices WHERE notice_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $notice_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('공지사항이 삭제되었습니다.');
                location.href = 'notice.php';
              </script>";
    } else {
        echo "공지사항 삭제 중 오류가 발생했습니다: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>
            alert('권한이 없거나 잘못된 요청입니다.');
            location.href = 'notice.php';
          </script>";
    exit;
}
?>
