<?php
// 데이터베이스 연결 설정
$host = 'localhost'; // 데이터베이스 호스트 (보통 localhost)
$db = 'your_database_name'; // 데이터베이스 이름
$user = 'your_username'; // 데이터베이스 사용자 이름
$pass = 'your_password'; // 데이터베이스 비밀번호
$charset = 'utf8mb4'; // 문자 인코딩 설정

// PDO 데이터 소스 이름(DSN)과 연결 옵션 설정
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // 예외를 통한 오류 처리
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // 연관 배열 형태로 데이터 가져오기
    PDO::ATTR_EMULATE_PREPARES   => false, // SQL 명령의 네이티브 프리페어 사용
];

try {
    // PDO 객체 생성 및 데이터베이스 연결
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // 데이터베이스 연결 실패 시 오류 메시지 출력 후 종료
    die("데이터베이스 연결 실패: " . $e->getMessage());
}

// 회원가입 폼에서 데이터가 POST 방식으로 전송되었는지 확인
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST 요청으로 전달된 입력 값 가져오기
    $name = htmlspecialchars(trim($_POST['name'])); // 이름
    $student_id = htmlspecialchars(trim($_POST['student_id'])); // 학번
    $phone = htmlspecialchars(trim($_POST['phone'])); // 전화번호
    $password = htmlspecialchars(trim($_POST['password'])); // 비밀번호
    $role = htmlspecialchars(trim($_POST['role'])); // 권한(학생, 교수, 관리자)

    // 필수 입력 필드가 비어있는지 확인
    if (empty($name) || empty($student_id) || empty($phone) || empty($password) || empty($role)) {
        die("모든 필드를 입력해주세요."); // 누락된 필드가 있을 경우 에러 메시지 출력 후 종료
    }

    // 비밀번호 해시 처리 (암호화)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 관리자 코드 생성 (권한이 '관리자'일 경우)
    $admin_code = null; // 기본값은 null
    if ($role === 'admin') {
        $admin_code = 'ADMIN-' . strtoupper(bin2hex(random_bytes(4))); // 랜덤 8자리 코드 생성
    }

    // 사용자 정보를 데이터베이스에 삽입하는 SQL 쿼리
    $sql = "INSERT INTO users (name, student_id, phone, password, role, admin_code) 
            VALUES (:name, :student_id, :phone, :password, :role, :admin_code)";
    $stmt = $pdo->prepare($sql); // SQL 준비 (프리페어드 스테이트먼트)

    try {
        // SQL 실행 및 데이터 삽입
        $stmt->execute([
            ':name' => $name,
            ':student_id' => $student_id,
            ':phone' => $phone,
            ':password' => $hashed_password,
            ':role' => $role,
            ':admin_code' => $admin_code
        ]);

        // 회원가입 성공 메시지 출력
        echo "회원가입이 완료되었습니다.<br>";
        if ($admin_code) {
            // 관리자일 경우 생성된 관리자 코드 출력
            echo "생성된 관리자 코드: $admin_code";
        }
    } catch (PDOException $e) {
        // SQL 실행 중 오류 발생 시 에러 메시지 출력 후 종료
        die("회원가입 중 오류 발생: " . $e->getMessage());
    }
} else {
    // POST 방식이 아닌 경우 에러 메시지 출력
    echo "잘못된 요청 방식입니다.";
}
?>
