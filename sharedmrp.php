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
  <title>Document</title>
</head>
<body>
  <p  style="margin: 20px auto 30px auto; width: 90%;"><a href="top2.php".php>トップに戻る</a></p>
  <p>みんなのmorning routine一覧</p>
  <?php
  //My MRの一覧取得
  $stmt = $pdo->prepare("SELECT * FROM table1_1 WHERE SHARED=1 ORDER BY DOWNLOAD_NUM DESC");
  $status = $stmt->execute();
  if($status==false){
    sql_error($stmt);
  } else {
    while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ 
      echo '<p><a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">'.$r["ROUTINE_NAME"].'</a><p>';
    }
  }
  ?>
</body>
</html>