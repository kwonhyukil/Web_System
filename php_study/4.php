<?php

$str = "나는 자랑스러운 미국인이다.";
// str_replace("바꿀문자열","바뀐무자열","대상문자열");
$str1 = str_replace("미국인", "한국인", $str);
echo $str1;
echo "<br>";
?>

<?php
// is_int(); 정수 판별
$x = 33443;

if(is_int($x)) {
    echo "x 는 정수입니다";
} else {
    echo "x 는 정수가 아닙니다.";
}
echo "<br>";
?>

<?php
// is_float(); 실수 판별
$y = 33.8;

if(is_float($y)) {
    echo "y 는 실수입니다";
} else {
    echo "y 는 실수가 아닙니다.";
}
echo "<br>";
?>

<?php
// is_numeric();
$x = "안녕";

if ( is_numeric($x)) {
    echo "숫자입니다.";
} else {
    echo "숫자가 아닙니다.";
}


