<?php
//エラー内容表示させる
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();


//変数a1,b1,c1に送られてきたデータa,b,cを入れる
$user_id = "test1";
$MR_ID   = 1;
$step_ID = $_POST['step'];
$action  = $_POST['action'];
$period  = $_POST['period'];


//2. DB接続します  
include("funcs.php");  //funcs.phpを読み込む（関数群）
$pdo = db_conn();      //DB接続関数


//３．データ登録SQL作成（テンプレート）
$sql = "INSERT INTO table3_test(user_id,date,MR_ID,step_ID,action,end_time,period)VALUES(:user_id, sysdate(), :MR_ID, :step_ID, :action, sysdate(), :period)";  //可変
$stmt = $pdo->prepare($sql);
//bindValueはセキュリティ
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':MR_ID',   $MR_ID,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':step_ID', $step_ID, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':action',  $action,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':period',  $period,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();//SQL実行


//４．データ登録処理後
if($status==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    echo "Error: ";
    echo $error = $stmt->errorInfo().$sql;

  }else{
    echo "OK";

  }


?>