<?php
session_start();
require('./dbconnect.php');
require('./function.php');

//セッションにuseridが保存されている時は、自動でログイン
if(isset($_SESSION["user_id"])){
  header("Location:./talkcontents/index.php");
  exit();
}
//オートログイン情報がクッキーに保存されている時データベースと比較
if(isset($_COOKIE["auto_login"])){
  $auto_login=$db->prepare("SELECT * FROM auto_login WHERE auto_login_key=?");
  $auto_login->execute(array(h($_COOKIE["auto_login"])));
  $auto_login_info=$auto_login->fetch();
  
  //自動ログイントークンが一致したとき
  if($auto_login_info){
    session_regenerate_id(true);
    $_SESSION["user_id"]=$auto_login_info["user_id"];
    header("Location:./talkcontents/index.php");
    exit();
  }else{
    //クッキーとデータベースで、自動ログイントークンが一致しない場合は、クッキーからトークンを消去
    setcookie("auto_login",$auto_login_token,time()-60*60*24*7);
    header("Location:./index.php");
    exit();
  }
}

//emailとpasswordが入力して送られてきた時
if($_POST){
    //入力されたメールアドレスとパスワードをデータベースと照合
    if(!empty($_POST["email"])&&!empty($_POST["password"])){
        $login=$db->prepare("SELECT * FROM users WHERE email=? AND password=?");
        $login->execute(array(
            h($_POST["email"]),
            h(sha1($_POST["password"]))
        ));
        $user=$login->fetch();

        //一致するデータがあったとき
        if($user){
        //自動ログインにチェックが入っているとき
          if($_POST["auto_login"]==="on"){
              $auto_login_token=bin2hex(random_bytes(32));
              //自動ログイントークンをクッキーに保存
              setcookie("auto_login",$auto_login_token,time()+60*60*24*7);

              //ログインしたユーザの自動ログイントークンが既にdbにないか確認
              $exist=$db->prepare("SELECT * FROM auto_login WHERE user_id=?");
              $exist->execute(array($user["id"]));

              //既に自動ログイントークンが存在する場合は、データベースのトークンを更新
              if($exist->fetch()){
                $update_key=$db->prepare("UPDATE auto_login SET auto_login_key=? WHERE user_id=?");
                $update_key->execute(array(
                  $auto_login_token,
                  $user["id"]
                ));
              }
              //自動ログイントークンがない場合は新規でレコードを追加
              else{
                $state=$db->prepare("INSERT INTO auto_login VALUES(?,?)");
                $state->execute(array(
                $user["id"],
                $auto_login_token
                ));
            }
          }
            //ログインする際にはセッションidを更新する（セッションハイジャック対策）
            session_regenerate_id(true);
            $_SESSION["user_id"]=$user["id"];
            header("Location:./talkcontents/index.php");
            exit();
        }else{
            $error["input"]="nouser";
        }
    }else{
        $error["input"]="blank";
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>RecordTalkIdeas</title>
    <link rel="stylesheet" href="login_style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  </head>
  <body>
  <header>
      <div class="top-wrapper clearfix">
        <div class="create">
          <a href="./join/index.php">新規登録</a>
        </div>
        <div class="header-logo">
          <a href="./talkcontents/index.php"><img src="./RTI-rogo.png" width="400px" height="60px"/></a>
        </div>
      </div>
</header>
    
    <div class="login-wrapper">
      <div class="container">
        <p class="title">ログイン</p>
        <div class="form-wrapper">
            <form action="./index.php" method="post">
            <dl> 
                <dt>メールアドレス</dt>
                <dd><input type="email" name="email" placeholder="メールアドレス" value=<?=$_POST["email"]?>></dd>
                <dt>パスワード</dt>
                <dd><input type="password" name="password" placeholder="パスワード"></dd>
                <?php if($error["input"]==="nouser"):?>
                <p class="error">※ユーザが見つかりませんでした</p>
                <?php elseif($error["input"]==="blank"):?>
                <p class="error">※全て入力してください</p>
                <?php endif;?>
                <input class="check" type="checkbox" name="auto_login" value="on">次回から自動でログインする<br>
                <input class="btn" type="submit" value="ログイン" />
            </dl>
            </form>
        </div>
      </div>
    </div>
  </body>
</html>