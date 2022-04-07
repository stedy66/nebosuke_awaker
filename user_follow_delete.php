<?php
session_start();

include("funcs.php");
sschk();
$pdo = db_conn();

$follow_id = $_POST['follow_id'];
$followed_id = $_POST['followed_id'];


$sql = 'DELETE FROM follow_table WHERE follow_id=:follow_id && followed_id=:followed_id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':follow_id',  $follow_id,    PDO::PARAM_STR);
$stmt->bindValue(':followed_id',  $followed_id,    PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
} else {
    $url = "user_detail.php?USER_ID=" . $followed_id;
    header("Location:" . $url);
    exit();
}
