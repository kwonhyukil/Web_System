<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $table = $_POST["table"];

    if ($table == "free"){
        $board_title = "자유게시판";
    } elseif ($table == "download"){
        $board_title = "자료실";
    } elseif ($table == "notice"){
        $board_title = "공지사항";
    } else {
        $board_title = "문의 게시판";
    }
    ?>
    <h1><?= $board_title?></h1>
</body>
</html>