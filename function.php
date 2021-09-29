<?php
function h($str){
    return htmlspecialchars($str,ENT_QUOTES,"UTF-8");
}

function login_check(){
    session_start();
    if(!isset($_SESSION["user_id"])){
      header("Location:https://recordideas.herokuapp.com/index.php");
    }
}
?>