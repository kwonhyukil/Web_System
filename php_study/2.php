<?php
// xampp
// 웹서버, DB서버, php

// Apache, mariadb, php 컴파일러

$txt = "PHP";
echo "I love $txt ! ";
echo "<br>";
?>

<?php
// PHP code goes here

// PHP 8
// PHP 7.4
// PHP3

// : 싱글 주석

/*
여러줄 주석
*/

?>

<?php

$txt = "안녕하세요";
$x = 5 ; // 정수
$y = 10.5 ; // 실수
$X = 10;
// 변수의 대소문자를 구분한다.
echo "x= $x, X= $X";
$aaaA = "";
$aaaa = "";
echo "<br>";
?>

<?php
// 전역변수
// 로컬변수 (지역변수)

// 전역변수
$x = 5;

function myTest(){
    // 지역변수 ( 로컬 변수 )
    $x = 3;
    echo "변수 x의 출력값 : $x"; // $x 의 출력값 : 3
    echo "<br>";
    $x++;
}

myTest();

echo "변수의 x의 출력값 : $x"; // $x 의 출력값 : 5

?>

