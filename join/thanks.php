<?php
session_start();

if(empty($_SESSION["token"]) || $_SESSION["token"]!=$_GET["key"]){
    header("Location:index.php");
}
else{
    unset($_SESSION["token"]);
}
?>

<!DOCTYPE html>
<meta charset="utf-8">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
    <link rel="stylesheet" href="join_style.css">
</head>
<body>
    <p>会員登録が完了しました</p>
    <a href="../index.php">ログインする</a>
</body>
</html>