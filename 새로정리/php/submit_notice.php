<?php
// 데이터베이스 연결 설정
$host = 'localhost'; // 호스트 이름
$db = 'il_database'; // 데이터베이스 이름
$user = 'root'; // 사용자 이름
$pass = 'gsc1234!@#$'; // 비밀번호
$charset = 'utf8mb4'; // 문자 인코딩 설정

// PDO 연결 옵션 설정
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // PDO 객체 생성 및 데이터베이스 연결
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("데이터베이스 연결 실패: " . $e->getMessage());
}

// POST 요청인지 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 입력값 가져오기
    $notice_name = trim($_POST['notice_name']);
    $title = trim($_POST['title']);
    $date = trim($_POST['date']);
    $target = trim($_POST['target']);
    $author = trim($_POST['author']);
    $content = trim($_POST['content']);

    // 필수 입력값 확인
    if (empty($notice_name) || empty($title) || empty($date) || empty($target) || empty($author) || empty($content)) {
        echo "<script>alert('모든 필드를 입력해주세요.'); history.back();</script>";
        exit();
    }

    // 데이터 삽입 쿼리
    $sql = "INSERT INTO NOTICES (notice_name, title, date, target, author, content) 
            VALUES (:notice_name, :title, :date, :target, :author, :content)";

    $stmt = $pdo->prepare($sql);

    try {
        // 데이터베이스에 데이터 삽입
        $stmt->execute([
            ':notice_name' => $notice_name,
            ':title' => $title,
            ':date' => $date,
            ':target' => $target,
            ':author' => $author,
            ':content' => $content
        ]);

        // 성공 메시지 및 리다이렉트
        echo "<script>alert('공지사항이 성공적으로 등록되었습니다.'); location.href='notice.php';</script>";
        exit();
    } catch (PDOException $e) {
        // 오류 메시지 출력
        echo "<script>alert('공지사항 등록 중 오류가 발생했습니다: " . $e->getMessage() . "'); history.back();</script>";
        exit();
    }
} else {
    echo "<script>alert('잘못된 요청입니다.'); history.back();</script>";
    exit();
}
?>
