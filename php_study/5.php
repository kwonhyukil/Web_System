<?php
// abs() 절대값

$a = -33;
$b = abs($a);

echo $b;
echo "<br>";
?>

<?php
// sqrt() 루트

$b = sqrt(4);

echo $b;
echo "<br>";
?>

<?php
// round() 반올림

$a = -2.75;
$b = round($a);
echo $b;
echo "<br>";
?>

<?php
// rand() 난수
// rand(범위 시작, 범위 끝)
$a = rand();

echo $a;
echo "<br>";
?>

<?php
// 상수
define("GREETING", "안녕하세요.");

echo GREETING;

?>