<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();


$id = $_SESSION['USER_ID'];

$icon = fileUpload("upfile", "upload/");
if ($icon == 1 ) {
  exit("file error");
}else if( $icon == 2) {
  $icon = 'NULL';
}


$stmt = $pdo->prepare("UPDATE table4 SET icon=:icon, EMAIL=:EMAIL,  ADDRESS=:ADDRESS, TWITTER=:TWITTER WHERE USER_ID=:USER_ID");
$stmt->bindValue(':USER_ID', $id, PDO::PARAM_INT);
$stmt->bindValue(':icon', $icon, PDO::PARAM_STR);
$stmt->bindValue(':EMAIL', $_POST["EMAIL"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':ADDRESS', $_POST["ADDRESS"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':TWITTER', $_POST["TWITTER"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行

if ($status === false) {
    sql_error($stmt);
} else {
    redirect("top2.php");
}

?>