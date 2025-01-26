<?php
require 'db.php'; // db.php 파일 연결

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 입력 데이터 수신
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);

    // 데이터베이스에 저장
    try {
        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        echo "<h1>Data Saved to Database</h1>";
        echo "<p>Name: $name</p>";
        echo "<p>Email: $email</p>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<p>Invalid request method.</p>";
}
?>
