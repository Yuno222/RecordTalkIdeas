<?php 
session_start();
require('../dbconnect.php');
require('../function.php');

//直リンク対策トークン生成
$token=bin2hex(random_bytes(32));
$_SESSION["token"]=$token;

if(!empty($_POST)){

    //nameチェック
    if(empty($_POST["name"])){
        $error["name"]="blank";
    }

    //emailチェック
    if(empty($_POST["email"])){
        $error["email"]="blank";
    }
    else{
        $member=$db->prepare("SELECT COUNT(*) as cnt FROM users where email=?");
        $member->execute(array(h($_POST["email"])));
        $record=$member->fetch();
        if($record["cnt"]>0){
            $error["email"]="duplicate";
        }
    }

    //psswordチェック
    if(empty($_POST["password"])){
        $error["password"]="blank";
    }
    elseif(strlen($_POST["password"])<5){
        $error["password"]="length";
    }

    //genderチェック
    if(empty($_POST["gender"])){
        $error["gender"]="blank";
    }

    //ageチェック
    if(empty($_POST["age"])||$_POST["age"]==="none"){
        $error["age"]="blank";
    }

    //エラーチェックを全て通過した時
    if(empty($error)){
        $_SESSION["user_info"]=$_POST;
        header("Location:check.php?key=$token");
        exit();
    }
}

//修正の場合は、セッションの値をPOSTへ代入
if($_GET["state"]==="rewrite"){
    $_POST=$_SESSION["user_info"];
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
<header>
      <div class="top-wrapper clearfix">
        <div class="create">
          <a href="../index.php">ログイン</a>
        </div>
        <div class="header-logo">
          <a href="../talkcontents/index.php"><img src="../RTI-rogo.png" width="400px" height="60px"/></a>
        </div>
      </div>
</header>

<div class="input-wrapper">
    <ul>
    <form  action="index.php" method="post" enctype="multipart/form-data">
        <li><p>氏名</p></li>
        <p class="error"><?=$error["name"]==="blank"?"※氏名が入力されていません":""?></p>
        <input type="text" name="name" placeholder="氏名" value="<?=h($_POST["name"])?>">

        <li><p>メールアドレス</p></li>
        <p class="error"><?=$error["email"]==="blank"?"※メールアドレスが入力されていません":""?></p>
        <p class="error"><?=$error["email"]==="duplicate"?"※登録済みのメールアドレスです":""?></p>
        <input type="email" name="email" placeholder="メールアドレス" value="<?=h($_POST["email"])?>">

        <li><p>パスワード</p></li>
        <p class="error"><?=$error["password"]==="blank"?"※パスワードが入力されていません":""?></p>
        <p class="error"><?=$error["password"]==="length"?"※パスワードが短すぎます":""?></p>
        <input type="password" name="password" placeholder="パスワード" value="<?=h($_POST["password"])?>">

        <li><p>性別 :
        <input type="radio" name="gender" value="男性" <?=$_POST["gender"]!="男性"?:"checked"?>>男
        <input type="radio" name="gender" value="女性" <?=$_POST["gender"]!="女性"?:"checked"?>>女
        </p></li>
        <p class="error"><?=$error["gender"]==="blank"?"※性別が選択されていません":""?></p>

        <li><p>年齢 :
        <select name="age">
            <option value="none">-</option>
            <?php for($i=1;$i<=100;$i++):?>
                <option value="<?php echo $i?>" <?=h($_POST["age"])!=$i?:"selected"?>><?php echo $i?></option>
            <?php endfor;?>
        </select>
        </p></li>
        <p class="error"><?=$error["age"]==="blank"?"※年齢が選択されていません":""?></p>
        <br>
        <input class="input-btn" type="submit" value="確認画面へ">
    </form>
    <ul>
</div>
</body>
</html>