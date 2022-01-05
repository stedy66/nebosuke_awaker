<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/mrplist.css">
  <title>Document</title>
</head>
<body>
  <header>みんなのmorning routine一覧</header>
  <p style="margin: 20px auto 30px auto; width: 600px;"><a href="top2.php".php>トップに戻る</a></p>
  <?php
  //シェアされたMRの一覧取得
  $stmt = $pdo->prepare("SELECT * FROM table1_1 WHERE SHARED=1 ORDER BY DOWNLOAD_NUM DESC");
  $status = $stmt->execute();
  if($status==false){
    sql_error($stmt);
  } else {
    while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ 
      $view='';
      $view.='<a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">';
      $view.='<div class="mrp" style="background-image: url(upload/default_bg.jpg);">';
      $view.='<img src="img/ネボスケロゴ_文字黒.jpg" class="logo">';
      $view.='<p>'.$r["ROUTINE_NAME"].'<p>';
      $view.='</div>';
      $view.='</a>';
      echo $view;
    }
  }
  ?>
</body>
</html>