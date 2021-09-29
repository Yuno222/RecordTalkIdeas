<script>
  function logout_alert(){
    return confirm("ログアウトしますか？");
  }
</script>

<?php 
require("../dbconnect.php");
require("../function.php");

//ログイン状態をチェック
login_check();

$categories=$db->query('SELECT * FROM categories');
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
      <div class="top-wrapper clearfix">
        <div class="create">
          <a href="../logout.php" onclick="return logout_alert()">ログアウト</a>
        </div>
        <div class="header-logo">
          <a href="./index.php"><img src="../RTI-rogo.png" width="400px" height="60px"/></a>
        </div>
      </div>
  </header>


  <div class="contents-wrapper">
      <div class="container">
        <p class="title">ジャンル一覧</p>
        <?php foreach($categories as $category):?>
          <div class="content">
            <a href="list.php?category=<?php echo $category["id"]?>"><?php echo $category["category_name"]?></a>
          </div>
        <?php endforeach;?>
        </div>
      </div>
    </div>
  </body>
</html>