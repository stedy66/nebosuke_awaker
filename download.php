<?php
session_start();
include("funcs.php");
$pdo = db_conn();
//２．ルーティンパッケージ抽出SQL作成
$stmt = $pdo->prepare("INSERT INTO table2(USER_ID,MR_ID)VALUES(:USER_ID, :MR_ID)");
$stmt->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_STR);
$stmt->bindValue(":MR_ID", $_GET["MR_ID"], PDO::PARAM_INT);
$status = $stmt->execute();
if($status==false) {
  sql_error($stmt);
}

$stmt = $pdo->prepare("UPDATE table1_1 SET DOWNLOAD_NUM=DOWNLOAD_NUM+1 WHERE MR_ID=:MR_ID");
$stmt->bindValue(":MR_ID", $_GET["MR_ID"], PDO::PARAM_INT);
$status = $stmt->execute();
if($status==false) {
  sql_error($stmt);
}

//３．データ作成
$_SESSION["download"]=1;
redirect("top2.php");
?>