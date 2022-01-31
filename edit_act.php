<?php
session_start();

include("funcs.php");
sschk();
$pdo = db_conn();

$stmt = $pdo->prepare("UPDATE table1_1 SET USER_ID=:USER_ID, ROUTINE_NAME=:ROUTINE_NAME, IMG_URL=:IMG_URL, DOWNLOAD_NUM=0, DESCRIPTION=:DESCRIPTION, YOUTUBE=:YOUTUBE, SHARED=:SHARED, CREATE_DATE=sysdate() WHERE MR_ID=:MR_ID");




$stmt->bindValue(':USER_ID', $_SESSION["USER_ID"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':ROUTINE_NAME', $_POST["ROUTINE_NAME"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':IMG_URL', "", PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':DESCRIPTION', $_POST["DESCRIPTION"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':YOUTUBE', $_POST["YOUTUBE"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)

$stmt->bindValue(':MR_ID', $_GET["MR_ID"], PDO::PARAM_INT);
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
}


//urlの後ろに添えたMR_IDを代入している
$MR_ID= $_GET["MR_ID"];

// //ルーティンの工程数SEQUENCEの数を数えて$countColumnに代入している
// $stmt3 = $pdo->prepare("SELECT COUNT(*) FROM  table1_3 WHERE MR_ID=:MR_ID");

// $stmt3->bindValue("MR_ID", $MR_ID, PDO::PARAM_INT);

// $status3 = $stmt3->execute();
// if ($status3 == false) {
//   sql_error($stmt);
// }

// $countColumn = $stmt3->fetchColumn();

$i=1;
$j = 1;

//データの更新のためルーティーンのデータが一つでもあれば該当レコードを削除
while (isset($_POST["STEP_ID" . $j]) && $_POST["STEP_ID" . $j] != 0) {
  $stmt4 = $pdo->prepare("DELETE FROM table1_3 WHERE MR_ID=:MR_ID");
  $stmt4->bindValue(":MR_ID", $MR_ID, PDO::PARAM_INT);
  $status4 = $stmt4->execute();
  if ($status4 == false) {
    sql_error($stmt4);
  }
  $j++;
}

while (isset($_POST["STEP_ID".$i]) && $_POST["STEP_ID".$i]!=0) {
  $stmt2 = $pdo->prepare("INSERT INTO table1_3(MR_ID, SEQUENCE, STEP_ID, DESCRIPTION, PERIOD)VALUES(:MR_ID, :SEQUENCE, :STEP_ID, :DESCRIPTION, :PERIOD)");
  $stmt2->bindValue(':MR_ID', $MR_ID, PDO::PARAM_INT);      //Integer（数値の場合 PDO::PARAM_INT)
  $stmt2->bindValue(':SEQUENCE', $i, PDO::PARAM_INT);      //Integer（数値の場合 PDO::PARAM_INT)
  $stmt2->bindValue(':STEP_ID', $_POST["STEP_ID".$i], PDO::PARAM_INT);      //Integer（数値の場合 PDO::PARAM_INT)
  $stmt2->bindValue(':DESCRIPTION', $_POST["DESCRIPTION".$i], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
  $stmt2->bindValue(':PERIOD', $_POST["PERIOD".$i]/1, PDO::PARAM_INT);      //Integer（数値の場合 PDO::PARAM_INT)
  $status2 = $stmt2->execute(); //実行
  if($status2==false) {
    sql_error($stmt2);
  }
  $i++;
}

// $stmt = $pdo->prepare("INSERT INTO table2(USER_ID, MR_ID)VALUES(:USER_ID, :MR_ID)");
// $stmt->bindValue(':USER_ID', $_SESSION["USER_ID"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
// $stmt->bindValue(':MR_ID', $MR_ID, PDO::PARAM_INT);      //Integer（数値の場合 PDO::PARAM_INT)
// $status = $stmt->execute(); //実行
// if($status==false) {
//   sql_error($stmt);
// }

//mysql_insert_id()は直近でinsertした行のidを取得するphpの関数
redirect("mrdetail.php?MR_ID=".$MR_ID);

?>