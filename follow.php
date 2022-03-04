<?php
session_start();

include("funcs.php");
sschk();
$pdo = db_conn();

$MR_ID = $_GET['MR_ID'];

$follow_id = $_POST['current_user_id'];
$followed_id = $_POST['follow_user_id'];



$sql = "INSERT INTO follow_table(id, follow_id, followed_id, indate)VALUES(NULL, :follow_id, :followed_id, sysdate())";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':follow_id',  $follow_id,    PDO::PARAM_STR);
$stmt->bindValue(':followed_id',  $followed_id,    PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
} else {
    $url = "mrdetail.php?MR_ID=" . $MR_ID;
    header("Location:" . $url);
    exit();
}


?>