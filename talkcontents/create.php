<?php
require('../function.php');
require('../dbconnect.php');

//ログイン状態をチェック
login_check();

if(empty($_GET["category"])||empty($_GET["state"])){
    header("Location:./index.php");
}

$member_id=$_SESSION["user_id"];
$category_id=h($_GET["category"]);
$state=h($_GET["state"]);

if(!empty($_GET["id"])){
    $idea_id=h($_GET["id"]);
}

if($state==="delete"){
    $delete=$db->query("DELETE FROM ideas WHERE id=$idea_id");
    header("Location:./list.php?category=$category_id");
    exit();
}

//ボタンが押された時の処理
if(!empty($_POST)){
    //作成ボタンが押された時
    if(!empty($_POST["createbtn"])){
        if(!empty($_POST["title"])){
            //新規作成の時
            if($state==="new"){
                $insert=$db->prepare('INSERT INTO ideas VALUES(0,?,?,?,?,NOW(),NOW())');
                $insert->execute(array(
                h($_POST["title"]),
                h($_POST["self_evaluate"]),
                $category_id,
                $member_id
            ));
            }

            elseif($state==="edit"){
                $modify=$db->prepare('UPDATE ideas SET title=?, self_evaluate=?, modified=NOW() WHERE id=?');
                $modify->execute(array(h(
                    $_POST["title"]),
                    h($_POST["self_evaluate"]),
                    $idea_id,
                ));
            }

            header("Location:./list.php?category=$category_id");
            exit();

            }
            else{
            $error["title"]="blank";
            }
        }
    }

    //戻るボタンが押された時
    if(!empty($_POST["backbtn"])){
        header("Location:./list.php?category=$category_id");
        exit();
    }


//編集状態の時の処理
if($state==="edit"){
    $category_contents=$db->prepare('SELECT * FROM ideas where id=?');
    $category_contents->execute(array($idea_id));
    $idea = $category_contents->fetch();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecordTalkIdeas</title>
    <link rel="stylesheet" href="create_style.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

  </head>

  <header>
      <div class="top-wrapper clearfix">
        <div class="header-logo">
          <a href="./index.php"><img src="../RTI-rogo.png" width="400px" height="60px"/></a>
        </div>
      </div>
  </header>

  <body>
     <div class="create-wrapper">
       <div class="create-form">
         <p class="main-title">
            <?php if($state==="new"):?>
                <?php echo "新規作成"?>
            <?php elseif($state==="edit"):?>
                <?php echo "編集"?>
            <?php endif?>
        </p>
         <p class="title-text">タイトル</p>
         <form action="./create.php?category=<?=$category_id?>&id=<?=$idea_id?>&state=<?=$state?>" method="post">
            <input type="text" class="title-form" name="title" size="20" maxlength="10" value="<?=$idea["title"]?>">
            <p>自己評価</p>
            <select class="self_evaluate" name="self_evaluate">
                <?php for($i=5;$i>=1;$i--):?>
                    <option value="<?=$i?>" <?php if($idea["self_evaluate"]===$i) echo 'selected'?>>
                    <?=$i?>
                </option>
                <?php endfor?>
            </select>
            <br>
            <input type="submit" name="backbtn" class="btn" value = "戻る">
            <input type="submit" name="createbtn" class="btn" value = "作成">
            <?php if($error["title"]==="blank"):?>
            <p class=error>※タイトルを入力してください。</p>
             <?php endif?>
         </form>
       </div>
     </div>
  </body>
</html>