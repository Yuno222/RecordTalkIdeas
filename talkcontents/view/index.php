<script>
  function delete_alert(){
   return confirm("本当に消去してもよろしいですか？");
  }
</script>

<?php 
require("../../function.php");
require("../../dbconnect.php");

//ログイン状態をチェック
login_check();

//getの取得
if(!empty($_GET["category"])){
  $category_id=h($_GET["category"]);
}

if(!empty($_GET["idea"])){
  $idea_id=h($_GET["idea"]);
}

//categoryとideaのid情報
$getinfo="category=$category_id&idea=$idea_id";


//テキストが送信されてきた場合
if(!empty($_POST["input-text"])){
    try{
        $db->beginTransaction();

        //talksにテキストを追加
        $state=$db->prepare('INSERT INTO talks VALUES(0,?,?,?,NOW())');
        $state->execute(array(
          h($_POST["input-text"]),
          h($_POST["which"]),
          $idea_id
        ));

        //ideasのmodifiedを更新
        $state=$db->prepare('UPDATE ideas SET modified=NOW() WHERE id=?');
        $state->execute(array(
          $state->execute(array($idea_id))
        ));

        $db->commit();
    }
    catch(PDOException $e){
      
      $db->rollback();
      echo "送信失敗：".$e->getMessage();
    }

    header("Location:index.php?$getinfo");
    exit();
}

//トークデータ取得
$talk_contents=$db->prepare('SELECT id, talk_text, speaker FROM talks WHERE idea_id=? ORDER BY created ASC');
$talk_contents->execute(array($idea_id));

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
          <a  href="../list.php?category=<?php echo $category_id?>"><span class="fa fa-arrow-left"></span></a>
          <img class="opp-icon icon" src="https://icon-rainbow.com/i/icon_06261/icon_062610.svg" width=50 height="50">
          <img class="my-icon icon" src="https://icon-rainbow.com/i/icon_05137/icon_051370.svg" width="50" height="50">
          <p>作成</p>
        </div>
      </div>
    </header>
      
    <div class="talk-wrapper clearfix">
      <div class ="container">
        <?php foreach($talks as $talk):?>
            <?php if($talk["speaker"]===1):?>
                <p class="opp-text talk-contents"><?php echo $talk["talk_text"]?><br>
                <a href="./delete.php?<?php echo $getinfo."&id=".$talk["id"]?>" onclick="return delete_alert()">消去</a>
                |
                <a href="./edit.php?<?php echo $getinfo."&id=".$talk["id"]?>">編集</a>
                </p>
            <?php else:?>
                <p class="my-text talk-contents"><?php echo $talk["talk_text"]?><br>
                <a href="./delete.php?<?php echo $getinfo."&id=".$talk["id"]?>" onclick="return delete_alert()">消去</a>
                |
                <a href="./edit.php?<?php echo $getinfo."&id=".$talk["id"]?>">編集</a>
              </p>
            <?php endif?>
          <?php endforeach?>
      </div>
    </div>
    
    <div class="input-wrapper">
      <div class="container">
        <form action="./index.php?idea=<?php echo $idea_id."&category=".$category_id?>" method="post">
          <textarea class="input-text" name="input-text"></textarea>
          <input type="radio" name="which" value=0 checked>自分
          <input type="radio" name="which" value=1>相手
          <input class="btn" type="submit" value="決定" >
        </form>
      </div>
    </div>
  </body>
</html>
