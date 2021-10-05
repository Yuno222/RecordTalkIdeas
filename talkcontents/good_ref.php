<?php
require("../dbconnect.php");
require("../function.php");

logincheck();

if(!empty($_POST["idea_id"])){
    $idea_id=$_POST["idea_id"];
    
    //いいねされている時(activeクラスを持っている時)は取り消して処理を終える
    if($_POST["nowstate"]==="true"){
        $delete=$db->query("DELETE FROM evaluation WHERE idea_id=$idea_id ORDER BY created DESC LIMIT 1");
        $count=$db->query("SELECT count(*) as good_num FROM evaluation WHERE idea_id=$idea_id");
        $good_num=$count->fetch();
        echo $good_num["good_num"];
        exit();
    }
    
    //いいねされていない時はいいね挿入
    $db->query("INSERT INTO evaluation VALUES(0,$idea_id,NOW())");
    $count=$db->query("SELECT count(*) as good_num FROM evaluation WHERE idea_id=$idea_id");
    $good_num=$count->fetch();
    echo $good_num["good_num"];

}else exit();

?>
