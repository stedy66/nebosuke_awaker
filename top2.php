<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();

//２．ユーザー取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM table4 WHERE USER_ID=:USER_ID");
$stmt->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_STR);
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if($status==false){
  sql_error($stmt);
}

//4. 抽出データ数を取得
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="css/top2style.css">
</head>
<body>
   
  <header>
  <img src="img/ネボスケロゴ_文字黒.jpg" class="logo">
  <a href="top2.php">ホーム
  </header>
  <?php
    if (isset($_SESSION["download"]) && $_SESSION["download"]==1) {
      echo "<p>ダウンロードしました。</p>";
      $_SESSION["download"]=0;
    }
    if (isset($_SESSION["delete"]) && $_SESSION["delete"]==1) {
      echo "<p>my morning routineから削除しました。</p>";
      $_SESSION["delete"]=0;
    }
  ?>
  <p style="margin: 20px auto 30px auto; width: 600px;">こんにちは、<?=$user["USER_NAME"]?></p>
  <p style="margin: 20px auto 30px auto; width: 600px;"><a href="mrpreg.php">新モーニングルーティンを作成する</a></p>
  <p style="margin: 20px auto 30px auto; width: 600px;">my morning routine　<a href="mymrp.php">すべて見る</a></p>
  <?php
  //My MRの一覧取得
  $stmt = $pdo->prepare("SELECT * FROM table2 LEFT JOIN table1_1 ON table2.MR_ID=table1_1.MR_ID WHERE table2.USER_ID=:USER_ID");
  $stmt->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_STR);
  $status = $stmt->execute();
  if($status==false){
    sql_error($stmt);
  } else {
    $i=0;
    while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){
      if ($i>=3) {
        break;
      } 
      echo '<p><a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">'.$r["ROUTINE_NAME"].'</a><p>';
      $i+=1;
    }
  }
  ?>
  <p style="margin: 20px auto 30px auto; width: 600px;">みんなのmorning routine　<a href="sharedmrp.php">すべて見る</a></p>
  <?php
  //みんなのMRの一覧取得
  $stmt = $pdo->prepare("SELECT * FROM table1_1 WHERE SHARED=1 ORDER BY DOWNLOAD_NUM DESC");
  $status = $stmt->execute();
  if($status==false){
    sql_error($stmt);
  } else {
    $i=0;
    while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){
      if ($i>=3) {
        break;
      } 
      echo '<p style="margin: 20px auto 30px auto; width: 600px;"><a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">'.$r["ROUTINE_NAME"].'</a><p>';
      $i+=1;
    }
  }
  ?>
</body>
</html>