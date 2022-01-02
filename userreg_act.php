<?php
session_start();
//POST値
$_SESSION["USER_ID"]=$_POST["USER_ID"];
$_SESSION["USER_NAME"]=$_POST["USER_NAME"];
$_SESSION["EMAIL"]=$_POST["EMAIL"];
$_SESSION["GENDER"]=$_POST["GENDER"];
$_SESSION["BIRTHDAY"]=$_POST["BIRTHDAY"];
$_SESSION["ADDRESS"]=$_POST["ADDRESS"];
$_SESSION["TWITTER"]=$_POST["TWITTER"];

//登録が成功するかどうか0:成功、1:失敗、-1:どちらでもない
$_SESSION["success"]=0;

//1. DB接続します
include("funcs.php");
$pdo = db_conn();

//2. 登録済みのlidとの重複チェック
$stmt = $pdo->prepare("SELECT COUNT(*) FROM table4 WHERE USER_ID=:USER_ID"); 
$stmt->bindValue(':USER_ID', $USER_ID, PDO::PARAM_STR);
$status = $stmt->execute();
//3. SQL実行時にエラーがある場合STOP
if($status==false){
  sql_error($stmt);
}
$count = $stmt->fetchColumn();
if ($count>0) {
  $_SESSION["duplicate"]=1;
  $_SESSION["success"]=1;
}

//パスワードの一致チェック
if ($lpw!=$lpw2) {
  $_SESSION["mismatch"]=1;
  $_SESSION["success"]=1;
}

//成功時、失敗時の処理
if ($_SESSION["success"]==1) {
  redirect("userreg.php");
}
else {
  //sqlとloginページへのリダイレクト
  $stmt = $pdo->prepare("INSERT INTO table4(USER_ID, USER_NAME, EMAIL, PASSWORD, GENDER, BIRTHDAY, ADDRESS, TWITTER, CREATE_DATE)VALUES(:USER_ID, :USER_NAME, :EMAIL, :PASSWORD,:GENDER, :BIRTHDAY, :ADDRESS, :TWITTER, sysdate())");
  $stmt->bindValue(':USER_ID', $_POST["USER_ID"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':USER_NAME', $_POST["USER_NAME"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':EMAIL', $_POST["EMAIL"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':PASSWORD', password_hash($_POST["PASSWORD"], PASSWORD_DEFAULT), PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':GENDER', $_POST["GENDER"], PDO::PARAM_INT);      //Integer（数値の場合 PDO::PARAM_INT)
  //日付型はそのままだとDBの日付型に入らない
  if ($BIRTH=='') {
    $BIRTH='19000101';
  } else {
    $BIRTH=str_replace('-','',$_POST["BIRTHDAY"]);
  }
  $stmt->bindValue(':BIRTHDAY', $BIRTH, PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':ADDRESS', $_POST["ADDRESS"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':TWITTER', $_POST["TWITTER"], PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
  $status = $stmt->execute(); //実行
  if($status==false) {
    sql_error($stmt);
  }else{
    $_SESSION["USER_ID"]="";
    $_SESSION["USER_NAME"]="";
    $_SESSION["EMAIL"]="";
    $_SESSION["GENDER"]="";
    $_SESSION["BIRTHDAY"]="";
    $_SESSION["ADDRESS"]="";
    $_SESSION["TWITTER"]="";
    $_SESSION["duplicate"]=0;
    $_SESSION["mismatch"]=0;
    redirect("login.php");
  }
}
?>