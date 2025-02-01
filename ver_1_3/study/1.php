<?php
// signup.php (회원가입 처리 페이지)

// 1. 사용자가 제출한 폼 데이터(아이디와 비밀번호)를 수신
// trim() 함수로 양쪽 공백 제거
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// 2. 입력값 유효성 검사 (필수 항목)
// 빈 값이 존재하면 경고 메시지를 출력하고 이전 페이지로 돌아감
if (empty($username) || empty($password)) {
    echo "<script>alert('모든 값을 입력해야 합니다.'); history.back();</script>";
    exit();  // 이후 코드가 실행되지 않도록 종료
}

// 3. 데이터베이스(DB) 연결 설정 (필수 항목)
// mysqli 객체 생성 (DB 연결 정보 입력)
// 매개변수: 호스트명, 사용자명, 비밀번호, 데이터베이스명
$mysqli = new mysqli("localhost", "root", "", "your_database");

// 4. 중복 회원 검사 쿼리 작성 및 실행 (필수 항목)
// username이 이미 DB에 존재하는지 검사하는 SQL 쿼리 준비
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $mysqli->prepare($query);  // SQL 문 준비
$stmt->bind_param("s", $username);  // 쿼리에서 ? 자리에 사용자 입력값 $username을 바인딩
$stmt->execute();  // 쿼리 실행
$result = $stmt->get_result();  // 실행 결과 가져오기

// 5. 중복 확인 결과 처리 (필수 항목)
// 중복된 username이 있으면 경고 메시지를 출력하고 이전 페이지로 돌아감
if ($result->num_rows > 0) {
    echo "<script>alert('이미 사용 중인 아이디입니다. 다른 아이디를 선택해주세요.'); history.back();</script>";
    exit();  // 코드 종료
}

// 6. 새로운 사용자 정보 삽입 쿼리 작성 (필수 항목)
// 유효한 경우 새로운 username과 암호화된 비밀번호를 DB에 삽입
$insert_query = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $mysqli->prepare($insert_query);  // 삽입 쿼리 준비

// 비밀번호 암호화 처리 (필수 항목)
// 암호화 알고리즘: BCRYPT (추천 알고리즘)
$password_hashed = password_hash($password, PASSWORD_BCRYPT);

// SQL 쿼리에 username과 암호화된 비밀번호를 바인딩
$stmt->bind_param("ss", $username, $password_hashed);

// 7. 쿼리 실행 및 회원가입 성공/실패 처리 (필수 항목)
// 실행 결과에 따라 성공 시 로그인 페이지로 리다이렉트, 실패 시 오류 메시지 출력
if ($stmt->execute()) {
    header("Location: login.php");  // 성공 시 로그인 페이지로 이동 (리다이렉트)
    exit();  // 코드 종료
} else {
    echo "<script>alert('회원가입에 실패했습니다. 다시 시도해주세요.'); history.back();</script>";
    exit();  // 코드 종료
}
?>
