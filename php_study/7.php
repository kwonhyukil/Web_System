<?php

// print_r($_SERVER);

// echo $_SERVER['PHP_SELF'];

$ag =  $_SERVER['HTTP_USER_AGENT'];

if (strpos($ag, 'Chrome')) {
    echo '크롬 유저시군요';
} else {
    echo '크롬 유저가 아니시군요.';
}

?>

<a href="<?= $_SERVER['PHP_SELF']; ?>?a=b">b 값을 가져오기</a>

<?php
echo "당신의 IP는". $_SERVER['REMOTE_ADDR'] . "입니다."; 


?>
