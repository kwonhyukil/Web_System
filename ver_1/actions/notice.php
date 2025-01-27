<?php
// 데이터베이스 연결 정보
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'study_db';

// 데이터베이스 연결
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('데이터베이스 연결 실패: ' . $conn->connect_error);
}

// 공지사항 데이터 가져오기
$sql = "SELECT id, title, author, create_at FROM notice_board ORDER BY create_at DESC";
$result = $conn->query($sql);

// HTML 출력
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>공지사항 목록</title>

</head>
<body>
    <h1>공지사항</h1>
    <button onclick="location.href = '../public/notice_writing.html'">공지사항 작성</button>
    <button onclick="location.href = '../public/main_menu.html'">뒤로가기</button>
    <br><br>
    <table>
        <thead>
            <tr>
                <th>번호</th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // 공지사항 데이터 출력
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td><a href='read.php?id=" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['title']) . "</a></td>";
                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['create_at']) . "</td>";
                    echo "<td><a class='delete-button' href='delete.php?id=" . htmlspecialchars($row['id']) . "' onclick='return confirm(\"정말 삭제하시겠습니까?\");'>삭제</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>공지사항이 없습니다.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php
$conn->close();
?>
