<?php
session_start();

// 데이터베이스 연결
require_once 'db_connect.php';


// 데이터베이스 연결
$conn = connectDatabase();

// 폼 데이터 가져오기
$title=$_POST['notice_title'];
$content=$_POST['notice_content'];
$author=$_POST['notice_author'];
$target_grade=$_POST['target_grade'];

// 대상 학년이 '선택'일 경우 재입력 요청
if ($target_grade == "선택"){
    // 에러 메시지와 함께 이전 페이지로 리다이렉트트
    echo "<script>
            alert('대상 학년을 선택해 주세요.');
            history.back();
            </script>";
    exit;
}

echo $target_grade;
// SQL 쿼리 작성 및 실행행
$sql = "INSERT INTO notices (title, content, author, target_grade) VALUE (?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt -> bind_param('ssss', $title,$content,$author,$target_grade);
if ($stmt -> execute()) {
    // 성공 시 메시지 출력 및 리다이렉트
    echo "<script>
            alert('공지사항이 등록되었습니다.');
            location.href = 'notice.php';
            </script>";
} else {
    echo "공지사항 등록 중 오류가 발생했습니다: " . $stmt->error;
}

// 연결 종료
$stmt->close();
$conn->close();
?>