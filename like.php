<?php
session_start();

include("funcs.php");
sschk();
$pdo = db_conn();

$like_MR = $_GET['MR_ID'];

$like_user = $_POST['like_user'];


$sql = "INSERT INTO like_table(id, USER_ID, MR_ID, indate)VALUES(NULL, :USER_ID, :MR_ID, sysdate())";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':USER_ID',  $like_user,    PDO::PARAM_STR);
$stmt->bindValue(':MR_ID',  $like_MR,    PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
} else {
    $url = "mrdetail.php?MR_ID=" . $like_MR;
    header("Location:" . $url);
    exit();
}
