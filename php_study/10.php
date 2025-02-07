<?php
session_start();

if (isset($_SESSION['ss_name'])) {
    echo "이름은 ". $_SESSION['ss_name'] . "입니다. <br>";
} else {
    echo "이름을 모르겠어요.<br>";
}

if (isset($_SESSION['ss_age'])) {
    echo "나이는 ". $_SESSION['ss_age'] . "입니다.<br>";
} else {
    echo "나이를 모르겠어요.<br>";
}

?>

<a href="11.php">세션 삭제</a>
