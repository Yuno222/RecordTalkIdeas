<?php
require("../dbconnect.php");
require("../function.php");

if(!empty($_POST["idea_id"])){
    $idea_id=$_POST["idea_id"];
    
    //いいねされている時は取り消す
    if($_POST["nowstate"]==="true"){
        $delete=$db->query("DELETE FROM evaluation WHERE idea_id=$idea_id ORDER BY created DESC LIMIT 1");
        $count=$db->query("SELECT count(*) as good_num FROM evaluation WHERE idea_id=$idea_id");
        $good_num=$count->fetch();
        echo $good_num["good_num"];
        exit();
    }
    
    //いいね挿入
    $db->query("INSERT INTO evaluation VALUES(0,$idea_id,NOW())");
    $count=$db->query("SELECT count(*) as good_num FROM evaluation WHERE idea_id=$idea_id");
    $good_num=$count->fetch();
    echo $good_num["good_num"];

}else exit();

?>