<?php

// // 데이터베이스 연결 정보 설정
// $host = "localhost";                // 데이터베이스 호스트
// $username = "root";                 // 데이터베이스 사용자 이름
// $password = "";                     // 데이터베이스 비밀번호
// $database = "user_registration";    // 데이터 베이스 이름

// // 데이터베이스 연결 객체 생성
// $conn = new mysqli($host,$username,$password,$database);

// // 연결 실패 여부 확인
// if ($conn->connect_error){
//     // 연결 실패 시 종료료
//     die("데이터베이스 연결 실패" . $conn -> connect_error);
// }

require_once 'db_connect.php';


// 데이터베이스 연결
$conn = connectDatabase();

// 폼 데이터 불러오기
$name = $_POST['name'];         // 이름 입력값
$password = $_POST['password']; // 비밀번호 입력값
$email = $_POST['email'];       // 이메일 입력값
$phone = $_POST['phone'];       // 전화번호 입력값
$gender = $_POST['gender'];     // 성별 입력값
$role = $_POST['role'];         // 권한한 입력값 (student, professor, admin)

// 초기값 설정 (학생이 아닌 경우 NULL으로 설정)
$student_id = 2924;             // 학번 기본값
$grade = NULL;                  // 학년 기본값
$error_message = "";            // 오류 메시지 변수 초기화화

// 권한이 학생 (student)일 경우 추가 검사 진행
if ($role === 'student'){
    // 학번이 입력되지 않은 경우 오류 메시지 출력
    if (empty($_POST['student_id'])){
        $error_message = "학생은 학번을 입력해야 합니다.";
    }
    // 학년이 선택되지 않은 경우 오류 메시지 설정 
    elseif ($_POST['grade'] === '선택'){
        $error_message = "학생은 학년을 선택해야 합니다.";
    } 
    // 학번과 학년 값이 유효할 경우 변수에 할당당
    else {
        $student_id = ($_POST['student_id']);
        $grade = ($_POST['grade']);
    }
}

// 학번 중복 검사
if (empty($error_message)){
    // 학번 중복 여부를 확인하는 SQL 쿼리
    $check_student_id_sql = "SELECT student_id FROM users where student_id = ?";
    // Prepare statement 생성 및 이메일 바인딩
    $check_stmt = $conn->prepare($check_student_id_sql);
    $check_stmt->bind_param('s', $student_id);

    // 쿼리 실행 및 결과 저장
    $check_stmt->execute();         // 실행
    $check_stmt->store_result();    // 저장

    // 이메일이 이미 존재할 경우 오류 메시지 설정
    if ($check_stmt->num_rows > 0){
        $error_message = "이미 사용 중인 이메일입니다.";
    } 
    // prepared statement 종료
    $check_stmt->close();
}
// 데이터 삽입 SQL 작성
if (empty($error_message)){

    // 비밀번호를 안전하게 암호화
    $password = password_hash($password, PASSWORD_DEFAULT);
    
    // 사용자 정보를 데이터베이스에 삽입하는 SQL 쿼리
    $sql = "INSERT INTO users (name, student_id, grade, password, email, phone, gender, role) VALUE (?, ?, ?, ?, ?, ?, ?, ?)";
    // prepared statement 생성 및 변수 바인딩
    $stmt = $conn->prepare($sql);
    $stmt -> bind_param('ssssssss', $name, $student_id, $grade, $password, $email, $phone, $gender, $role);

    // 쿼리 실행 및 결과 확인
    if ($stmt->execute()) {
        // 회원가입 성공 시 로그인 페이지로 리다이렉트
        header("Location: ../html/login.html");
        exit; // 프로그램 종료
    } else {
        // 쿼리 실행 중 오류가 발생한 경우 오류 메시지 설정
        $error_message = "회원 가입중 오류가 발생했습니다: " . $stmt->error;
    }

    // prepared statement 종료
    $stmt->close();
}
// 데이터베이스 연결 종료
$conn->close();

?>