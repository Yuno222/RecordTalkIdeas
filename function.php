<?php
function h($str){
    return htmlspecialchars($str,ENT_QUOTES,"UTF-8");
}

//ログイン状態をチェック、セッションの開始、クリックジャッキング対策。
function login_check(){
    session_start();
    header("X-FRAME-OPTIONS: DENY");
    if(!isset($_SESSION["user_id"])){
      header("Location:https://recordideas.herokuapp.com/index.php");
    }
}
?>
