<?php 
//データベース接続
 try{
    $db=new PDO('mysql:dbname=heroku_31c35d343b1c7a6;host=us-cdbr-east-04.cleardb.com;charset=utf8','b8b40044c2ac91','74a080df',
  [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //連想配列
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //例外
    PDO::ATTR_EMULATE_PREPARES => false, //SQLインジェクション対策
  ]);
  }
  catch(PDOException $e){
    echo "接続エラー:" . $e->getmessage();
  }
?>
