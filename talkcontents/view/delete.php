<?php 
require('../../dbconnect.php');
require('../../function.php');

//ログイン状態をチェック
login_check();

$category_id=h($_GET["category"]);
$idea_id=h($_GET["idea"]);
$talk_id=h($_GET["id"]);

$referer = $_SERVER['HTTP_REFERER'];

//リファラ確認による直リンク禁止
if($referer!="https://recordideas.herokuapp.com/talkcontents/view/index.php?category=$category_id&idea=$idea_id"){
    header("Location:../../index.php");
    exit();
}

//メンバーidが一致すればトークデータ消去
$delete=$db->prepare('DELETE FROM talks WHERE id=?');
$delete->execute(array($talk_id));

header("Location:index.php?category=$category_id&idea=$idea_id");
exit();

?>
