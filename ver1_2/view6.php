<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
    $email1 = $_POST["email1"];
    $email2 = $_POST["email2"];
    ?>
<body>
    <ul>
        <li>이메일 : <?php echo $email1."@". $email2; ?><li>
    <ul>
</body>
</html>