<script>
  function delete_alert(){
   return confirm("本当に消去してもよろしいですか？");
  }
</script>

<?php
require('../function.php');
require('../dbconnect.php');

//ログイン状態をチェック
login_check();

$member_id=$_SESSION["user_id"];

if(!empty($_GET["category"])){
  $category_id=h($_GET["category"]);
}
else{
  header("Location:./index.php");
}

$getname=$db->prepare('SELECT * FROM categories WHERE id=?');
$getname->execute(array($category_id));
$category_data=$getname->fetch();

if(!empty($_POST["sort"])){
    h($sort=$_POST["sort"]);
}

//アイディア数取得
$getcount=$db->prepare('SELECT count(*) as cnt FROM ideas WHERE category_id=? AND member_id=?');
$getcount->execute(array($category_id,$member_id));
$count=$getcount->fetch();

//ページの指定がなければ、1ページ目を表示
if(!empty($_GET["page"])){
  $page=$_GET["page"];
}
else{
  $page=1;
}

//最大表示ページ
$maxpage=ceil($count["cnt"]/5);

//最大ページよりも指定ページが大きい場合、1ページ目を表示
if($page>$maxpage){
  $page=1;
}
$startdisp=$page*5-5;


if($sort){
    //ORDER BY句はプリペアドメソッドを使えないので、switch文で条件分岐
    switch($sort){
        case 'self_evaluate':
          $category_contents=$db->prepare("SELECT * FROM ideas WHERE category_id=? AND member_id=? ORDER BY self_evaluate DESC LIMIT $startdisp , 5");
          $category_contents->execute(array($category_id,$member_id));
        break;

        case 'modified':
            $category_contents=$db->prepare("SELECT * FROM ideas WHERE category_id=? AND member_id=? ORDER BY modified DESC LIMIT $startdisp , 5");
            $category_contents->execute(array($category_id,$member_id));
        break;

        default:
            $category_contents=$db->prepare("SELECT * FROM ideas WHERE category_id=? AND member_id=? ORDER BY created DESC LIMIT $startdisp , 5");
            $category_contents->execute(array($category_id,$member_id));
    }
}else{
    $category_contents=$db->prepare("SELECT * FROM ideas WHERE category_id=? AND member_id=? ORDER BY created DESC LIMIT $startdisp , 5");
    $category_contents->execute(array($category_id,$member_id));
}

$ideas=$category_contents->fetchAll();

//いいね数取得
$getgood=$db->query('SELECT ideas.id as idea_id, count(*) as good_num FROM evaluation JOIN ideas ON evaluation.idea_id=ideas.id GROUP BY idea_id');
$good_records=$getgood->fetchAll();


?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecordTalkIdeas</title>
    <link rel="stylesheet" href="list_style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  </head>
  <body>

  <header>
      <div class="top-wrapper clearfix">
        <div class="create">
          <a class="newcreate" href="create.php?category=<?php echo $category_id."&state=new"?>">新規作成</a>
        </div>
        <div class="header-logo">
          <a href="./index.php"><img src="../RTI-rogo.png" width="400px" height="60px" /></a>
        </div>
      </div>
      
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </header>

    <div class="title-wrapper">
        <a  href="index.php"><span class="fa fa-arrow-left"></span></a>
        <h2 class="title"><?php echo $category_data["category_name"]?></h2>
    </div>

    <div class="sort-wrapper"> 
        <div class="container">
         <form action="./list.php?category=<?php echo $category_id?>" method="post">
            <select class="sort" id="sort" name="sort">
                <option value="created" <?= $sort != 'created' ?: 'selected' ?>>作成順</option>
                <option value="self_evaluate" <?= $sort != 'self_evaluate' ?: 'selected' ?>>評価順</option>
                <option value="modified" <?= $sort != 'modified' ?: 'selected' ?>>更新順</option>
            </select>
            <input type="submit" value="並び替え">
         </form>
      </div>
    </div>

    <div class="info-wrapper">
      <div class="container">
      <dl>
        <?php foreach($ideas as $idea):?>
          <div class="idea-info">
            <dt><a href="./view/index.php?category=<?php echo $category_id."&idea=".$idea["id"]?>"><?php echo $idea["title"]?></a>
              <div class="evaluation-button" data-idea_id="<?=$idea["id"]?>">
                <span class="good fa fa-thumbs-up">
                  <?php foreach($good_records as $good_record):?>
                    <?php if($idea["id"]===$good_record["idea_id"]):?>
                      <?php echo $good_record["good_num"]?>
                    <?php endif?>
                  <?php endforeach?>
                </span>
              </div>
            </dt>
            <dd>自己評価：<?php echo $idea["self_evaluate"]?>/5</dd>
            <dd>作成日：<?php echo $idea["created"]?>
              <a href="./create.php?category=<?=$category_id?>&id=<?=$idea["id"]?>&state=edit">編集</a>
              <a href="./create.php?category=<?=$category_id?>&id=<?=$idea["id"]?>&state=delete" onclick="return delete_alert()">消去</a>
            </dd>
          </div>
        <?php endforeach?>
      </dl>
    </div>

    <div class="page">
      <?php if($page!=1):?>
        <form method="post" name="form1" action="./list.php?category=<?=$category_id?>&page=<?=$page-1?>">
          <input type="hidden" name="sort" value=<?=$sort?>>
          <a href="javascript:form1.submit()">前へ</a>
        </form>
      <?php endif?>
      
      <?php if($page!=$maxpage):?>
        <form method="post" name="form2" action="./list.php?category=<?=$category_id?>&page=<?=$page+1?>">
          <input type="hidden" name="sort" value=<?=$sort?>>
          <a href="javascript:form2.submit()">次へ</a>
        </form>
      <?php endif?>
    </div>
  </div>

    <script src="./good_ajax.js"></script>
  </body>
</html>