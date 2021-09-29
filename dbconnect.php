<?php 
 try{
    $db=new PDO('mysql:dbname=heroku_eccbca177deccc9;host=us-cdbr-east-02.cleardb.com;charset=utf8','bfb8515b7d9ba3','5734eda5',
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