<?php
require('./dbconnect.php');
require('./function.php');

//ログイン状態をチェック
login_check();

//クッキーの消去
setcookie("auto_login",$auto_login_token,time()-60*60*24*7);
setcookie("PHPSESSID", '', time() - 1800);

//セッションの消去
$_SESSION = array();
session_destroy();

header("Location:index.php")
?>