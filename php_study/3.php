<?php

echo "";

print "";

echo("3");
print("4");

?>

<?php

echo "<h2>PHP는 재밌다</h2>";
echo "Hello world! <br>";
echo "I'm about to learn PHP! <br>";

?>

<?php
$txt1 = "Learn PHP";
$x = 5;
$y = 5;
echo "<h2>".$txt1."</h2>";
echo $x + $y;
echo "<br>";
?>

<?php

$x = 3333;

// 문자형 확인함수
var_dump($x);

echo "<br>";

$y = "한글";

var_dump($y);

$y_len = strlen("한글");
echo $y_len;

?>

<?php
// str_word_count() 단어수 세기 (한글은 안됨)
echo str_word_count("Hello World"); // 2
echo "<br>";
// 문자열 뒤집는 함수
echo strrev("hello World");
echo "<br>";
$a = strpos ("Hello world", "world");
echo $a;
echo "<br>";

$email = "aaa@gmail.com";

if ( strpos($email,"@") ) {
    echo "이메일 형식에 맞음";
} else {
    echo "이메일 형식이 잘못됨";
}
?>