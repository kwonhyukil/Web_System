<?php

session_start();

// // 데이터베이스 연결 정보
// $host= "localhost";
// $username = "root";
// $password = "";
// $database = "user_registration";

// // 데이터베이스 연결
// $conn = new mysqli($host,$username,$password,$database);

// // 데이터베이스 연결 확인
// if ($conn->connect_error){
//     die("데이터베이스 연결 실패" . $conn->connect_error);
// }

require_once 'db_connect.php';

// 데이터베이스 연결
$conn = connectDatabase();


// 사용자 입력값 가져오기
$input_student_id = $_POST['student_id'];     // 사용자 입력 학번
$input_password = $_POST['password'];       // 사용자 입력 비밀번호호


echo $input_password;
// 사용자 검증을 위한 SQL 쿼리 작성
$sql = "SELECT user_id, name, password, role FROM users where student_id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $input_student_id);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1){
    $user = $result->fetch_assoc();

    if (password_verify($input_password, $user['password'])){

        session_start();
        $_SESSION['student_id'] = $user['student_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        echo "로그인 성공! 환영합니두." . $user['name']. "님~";
        header("Location: ../html/main_page.html");
        exit;
    } else {
        echo "비밀번호가 올바르지 않습니다.";
    }
} else {
    echo "해당 학번이 존재하지 않습니다.";
}

$stmt->close();
$conn->close();

?>