<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $gender = $_POST['gender'];
    $email_OK = $_POST['email_ok'];

    if ($email_OK == "예"){
        $email = "수신";
    } else {
        $email = "비수신";
    }
    ?>
    <ul>
        <li>성별 : <?= $gender?><li>
        <li>이메일 : <?= $email?><li>
    </ul>
    
</body>
</html>