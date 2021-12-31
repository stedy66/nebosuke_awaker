<?php
session_start();
include("funcs.php");
$pdo = db_conn();
//２．ルーティンパッケージ抽出SQL作成
$stmt = $pdo->prepare("INSERT INTO table2(USER_ID,MR_ID)VALUES(:USER_ID, :MR_ID)");
$stmt->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_INT);
$stmt->bindValue(":MR_ID", $_GET["MR_ID"], PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ作成
if($status==false) {
    sql_error($stmt);
}else{
  $SESSION["download"]=1;
  redirect("top2.php");
}
?>