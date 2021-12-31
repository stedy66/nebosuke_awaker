<?php
session_start();
$i=1;
while (isset($_POST["STEP_ID".$i])) {
  echo $_POST["STEP_ID".$i].'<br>';
  echo $_POST["DESCRIPTION".$i].'<br>';
  echo $_POST["TIME".$i].'<br>';
  $i++;
}

include("funcs.php");
sschk();
$pdo = db_conn();

$stmt = $pdo->prepare("INSERT INTO table1_1(USER_ID, ROUTINE_NAME, IMG_URL, DOWNLOAD_NUM, DESCRIPTION, YOUTUBE, SHARED, CREATE_DATE)VALUES(:USER_ID, :ROUTINE_NAME, :IMG_URL, 0, :DESCRIPTION, :YOUTUBE, :SHARED, sysdate())");
$stmt->bindValue(':USER_ID', $_SESSION["USER_ID"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':ROUTINE_NAME', $_POST["ROUTINE_NAME"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':IMG_URL', "", PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':DESCRIPTION', $_POST["DESCRIPTION"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':YOUTUBE', $_POST["YOUTUBE"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
//SHAREDにチェックが入っていたら1,入っていなかったら0
if (isset($_POST["SHARED"])) {
  $SHARED=1;
} else {
  $SHARED=0;
}
$stmt->bindValue(':SHARED', $SHARED, PDO::PARAM_INT);      //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行
if($status==false) {
  sql_error($stmt);
}else{
  //mysql_insert_id()は直近でinsertした行のidを取得するphpの関数
  redirect("mrdetail.php?MR_ID=".($pdo->lastInsertId()));
}
?>