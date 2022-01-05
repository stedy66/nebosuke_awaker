<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();

//1. 自分で作成したモーニングルーティンかを判定
$stmt = $pdo->prepare("SELECT * FROM table1_1 WHERE MR_ID=:MR_ID");
$stmt->bindValue(":MR_ID", $_GET["MR_ID"], PDO::PARAM_INT);
$status = $stmt->execute();
if($status==false){
  sql_error($stmt);
}else{
  $package = $stmt->fetch();
  if ($package["USER_ID"]!=$_SESSION["USER_ID"]) {
    echo '<p>削除可能なMRではありません。</p>';
    echo '<p><a href="top2.php".php>トップに戻る</a></p>';
    exit();
  }
}

//２．ルーティンパッケージ抽出SQL作成
$stmt = $pdo->prepare("DELETE FROM table1_1 WHERE MR_ID=:MR_ID");
$stmt->bindValue(":MR_ID", $_GET["MR_ID"], PDO::PARAM_INT);
$status = $stmt->execute();
if($status==false) {
  sql_error($stmt);
}
$stmt = $pdo->prepare("DELETE FROM table1_3 WHERE MR_ID=:MR_ID");
$stmt->bindValue(":MR_ID", $_GET["MR_ID"], PDO::PARAM_INT);
$status = $stmt->execute();
if($status==false) {
  sql_error($stmt);
}
$stmt = $pdo->prepare("DELETE FROM table2 WHERE MR_ID=:MR_ID");
$stmt->bindValue(":MR_ID", $_GET["MR_ID"], PDO::PARAM_INT);
$status = $stmt->execute();
if($status==false) {
  sql_error($stmt);
}

//３．データ作成
$_SESSION["delete"]=1;
redirect("top2.php");
?>