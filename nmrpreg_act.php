<?php
session_start();

include("funcs.php");
sschk();
$pdo = db_conn();

$stmt = $pdo->prepare("INSERT INTO table5(USER_ID, DATE, MR_ID, SHARED, COMMENT, START_TIME) VALUES (:USER_ID, :DATE, :MR_ID, 0, '', :START_TIME)");
$stmt->bindValue(':USER_ID', $_SESSION["USER_ID"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':DATE', $_POST["DATE"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':MR_ID', $_POST["MR_ID"], PDO::PARAM_INT);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':START_TIME', $_POST["START_TIME"].':00', PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行
if($status==false) {
  sql_error($stmt);
}

//mysql_insert_id()は直近でinsertした行のidを取得するphpの関数
redirect("log.php?DATE=".$_POST["DATE"]);
?>