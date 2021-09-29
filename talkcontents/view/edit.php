<?php 
require('../../dbconnect.php');
require('../../function.php');

//ログイン状態をチェック
login_check();

$category_id=h($_GET["category"]);
$idea_id=h($_GET["idea"]);
$talk_id=h($_GET["id"]);

$referer = $_SERVER['HTTP_REFERER'];

//categoryとideaのid情報
$getinfo="category=$category_id&idea=$idea_id";

//memberidはテスト
$member_id=1;

if(isset($_POST["rewrite-text"])){
  if($_POST["rewrite-text"]!=""){
    //トランザクション処理
    try{
      $db->beginTransaction();

      //テキスト挿入処理
      $state=$db->prepare('UPDATE talks SET talk_text=?,speaker=? WHERE id=?');
      $state->execute(array(
        h($_POST["rewrite-text"]),
        h($_POST["which"]),
        $talk_id
      ));
      //modifiedを更新
      $modify=$db->prepare('UPDATE ideas SET modified=NOW() WHERE id=?');
      $modify->execute(array($idea_id));

      $db->commit();
    }
    
    catch(PDOException $e){
        $db->rollback();
        echo "送信失敗：".$e->getMessage();
    }
      header("Location:index.php?$getinfo");
      exit();
  }
  else{
      $error["rewrite"]="blank";
    }
}

//トークデータ取得
$talk_contents=$db->prepare('SELECT id, talk_text, speaker FROM talks WHERE id=?');
$talk_contents->execute(array($talk_id));

$talks=$talk_contents->fetchAll();


?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecordTalkIdeas</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  </head>
  <body>
    <header>
      <div class="container">
        <div class="talk-info clearfix">
          <a  href="./index.php?<?php echo $getinfo ?>"><span class="fa fa-arrow-left"></span></a>
          <img class="opp-icon icon" src="https://icon-rainbow.com/i/icon_06261/icon_062610.svg" width=50 height="50">
          <img class="my-icon icon" src="https://icon-rainbow.com/i/icon_05137/icon_051370.svg" width="50" height="50">
          <p>編集</p>
        </div>
      </div>
    </header>
      
    <div class="talk-wrapper clearfix">
      <div class ="container">
        <?php foreach($talks as $talk):?>
            <?php if($talk["speaker"]===1): $speaker=1;?>
                <p class="opp-text talk-contents"><?php echo $talk["talk_text"]?></p>
            <?php else: $speaker=0;?>
                <p class="my-text talk-contents"><?php echo $talk["talk_text"]?></p>
            <?php endif?>
          <?php endforeach?>
      </div>
    </div>
    
    <div class="input-wrapper">
      <div class="container">
        <form action="./edit.php?<?php echo $getinfo."&id=".$talk_id?>" method="post">
        <?php if($error["rewrite"]==="blank"):?>
            <textarea class="input-text" name="rewrite-text" placeholder="空白はダメだよ"></textarea>
        <?php else:?>
            <textarea class="input-text" name="rewrite-text"></textarea>
        <?php endif?>
          <input type="radio" name="which" value=0 <?= $speaker===1 ?: 'checked'?>>自分
          <input type="radio" name="which" value=1 <?= $speaker===0 ?: 'checked'?>>相手
          </select>
          <input class="btn" type="submit" value="変更" >
        </form>
      </div>
    </div>
  </body>
</html>
