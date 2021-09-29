<?php 
    function h($str){
        return htmlspecialchars($str,ENT_QUOTES,"UTF-8");
    }

    require('../dbconnect.php');
    session_start();

    $token=$_SESSION["token"];
    //トークンチェック
    if($token!=$_GET["key"] || empty($_SESSION["token"]) || empty($_SESSION["user_info"])){
        header("Location:index.php");
        exit();
    }
    elseif(empty($_POST)){
        //POSTの値がないかつトークンが正しい時（初回のアクセス）はトークンを作り直す(リロード対策とCSRF対策)
        $token=bin2hex(random_bytes(32));
        $_SESSION["token"]=$token;
    }

    //hiddenで送信されてきたトークンの値が正しい時のみ登録処理
    if($_POST && $_POST["token"]===$token){
        $state=$db->prepare("INSERT INTO users VALUES(0,?,?,?,?,NOW(),NOW())");
        $state->execute(array(
            h($_SESSION["user_info"]["name"]),
            h($_SESSION["user_info"]["email"]),
            sha1(h($_SESSION["user_info"]["password"])),
            h($_SESSION["user_info"]["picture"])
        ));

        unset($_SESSION["user_info"]);
        header("Location:thanks.php?key=$token");
        exit();
    }

    
?>

<!DOCTYPE html>
<meta charset="utf-8">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="join_style.css">
</head>

<body>
<div class="input-wrapper">
    <p>★内容を確認して「登録する」ボタンを押してください</p>
    <ul>
    <form action="check.php?key=<?=$token?>" method="post">
        <input type="hidden" name="token" value=<?=$token?>>
        <li><p>氏名</p></li>
        <p class="check"><?=h($_SESSION["user_info"]["name"])?></p>

        <li><p>メールアドレス</p></li>
        <p class="check"><?=h($_SESSION["user_info"]["email"])?></p>

        <li><p>パスワード</p></li>
        <p >【表示しません】</p>

        <li><p>性別</li>
        <p class="check"><?=h($_SESSION["user_info"]["gender"])?></p>

        <li>年齢</li>
        <p class="check"><?=h($_SESSION["user_info"]["age"])?>歳</p>

        <a class="rewrite" href="index.php?state=rewrite">修正する</a> | <input class="input-btn" type="submit" value="登録する">
    </form>
    <ul>
</div>
</body>
</html>