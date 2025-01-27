<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <ul>
        <li> 나의 취미 :
        <?php
        $num = count($_POST["hobby"]);

        for ($i = 0 ; $i < $num ; $i++){
            echo $_POST["hobby"][$i];
            if ($i != $num - 1)
            echo ", ";
        }
        ?>
        
    </li>
    </ul>
</body>
</html>