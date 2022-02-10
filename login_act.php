<?php
//最初にSESSIONを開始！！ココ大事！！
session_start();

//1.  DB接続します
include("funcs.php");
$pdo = db_conn();

//2. データ登録SQL作成
//* PasswordがHash化→条件はUSER_IDのみ！！
$stmt = $pdo->prepare("SELECT * FROM table4 WHERE USER_ID=:USER_ID"); 
$stmt->bindValue(':USER_ID', $_POST["USER_ID"], PDO::PARAM_STR);
$status = $stmt->execute();
$stmt2 = $pdo->prepare("SELECT COUNT(*) FROM table4 WHERE USER_ID=:USER_ID"); 
$stmt2->bindValue(':USER_ID', $_POST["USER_ID"], PDO::PARAM_STR);
$status2 = $stmt2->execute();

//3. SQL実行時にエラーがある場合STOP
if($status==false){
    sql_error($stmt);
}
if($status2==false){
  sql_error($stmt2);
}

//4. 抽出データ数を取得
$val = $stmt->fetch();         //1レコードだけ取得する方法
$count = $stmt2->fetchColumn(); //SELECT COUNT(*)で使用可能()

//5.該当１レコードがあればSESSIONに値を代入
//入力したPasswordと暗号化されたPasswordを比較！[戻り値：true,false]
//$_SESSION["login"] 0:ログイン成功、1:アカウント存在しない、2:password不一致
if ($count==0) {
  $_SESSION["login"]=1;
  redirect("login.php");
}
else {
  //password_verify第一引数にはユーザー入力
  //第二引数にはpassword_hushでhush化したパスワード値（データベース参照）
  //bool値を返す
  $pw = password_verify($_POST["PASSWORD"], $val["PASSWORD"]);
  if($pw){
    //Login成功時
    $_SESSION["chk_ssid"]  = session_id();
    $_SESSION["USER_ID"]   = $val['USER_ID'];
    $_SESSION['icon'] = $val['icon'];
    //表示用の変数をリセット（たぶんやらなくても大丈夫）
    $_SESSION["duplicate"] = 0;
    $_SESSION["mismatch"] = 0;
    $_SESSION["success"]   =-1;
    $_SESSION["login"]=0;
    //Login成功時（リダイレクト）
    redirect("top2.php");
  }else{
    //Login失敗時(リダイレクト)
    $_SESSION["login"]=2;
    redirect("login.php");
  }
}
exit();
?>
