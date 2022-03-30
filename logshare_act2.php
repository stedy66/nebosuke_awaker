<?php
//SESSION開始！！
session_start();
//関数群の読み込み
include('funcs.php');
//LOGINチェック
sschk();
//DB接続します
$pdo = db_conn();

//POSTデータ取得
$date       = $_POST["date"];
$comment    = $_POST["comment"];
$evaluation = $_POST["evaluation"];

echo $date."\n".$comment."\n".$evaluation."\n";
// echo $comment;
// echo $evaluation;

//受信データ登録SQL作成
//prepare("")の中にはmysqlのSQLで入力したINSERT文を入れて修正すれば良いイメージ
// $stmt1 = $pdo->prepare("SELECT* FROM table3_test");
// $status1 = $stmt1->execute();

$stmt = $pdo->prepare("SELECT * FROM table2 INNER JOIN table3_test ON table2.MR_ID=table3_test.MR_ID 
                        WHERE table2.USER_ID=:USER_ID and date = '$date' ORDER BY step");
$stmt->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_STR);
$status = $stmt->execute();




//送信データ登録SQL作成（logshare_table）
$sql = "INSERT INTO logshare_table(USER_ID,date,comment,evaluation)VALUES(:USER_ID, :date, :comment, :evaluation)";
$stmt1 = $pdo->prepare($sql);
$stmt1->bindValue(':USER_ID',   $_SESSION["USER_ID"], PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt1->bindValue(':date',      $date,                PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt1->bindValue(':comment',   $comment,             PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt1->bindValue(':evaluation',$evaluation,          PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status1 = $stmt1->execute();//SQL実行

//データ登録処理後
if($status1==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    echo "Error: ";
    echo $error = $stmt1->errorInfo().$sql1;
  }

$LOG_ID=($pdo->lastInsertId());

//送信データ登録SQL作成（logsequence_table）
$sql = "INSERT INTO logsequence_table(LOG_ID,step_ID,action,time,plan,step,end_time)VALUES(:LOG_ID, :step_ID, :action, :time, :plan, :step, :end_time)";
$stmt2 = $pdo->prepare($sql);

if($status==false) {
    //SQLエラーの場合
    sql_error($stmt);
}else{
    //SQL成功の場合
    while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ //データ取得数分繰り返す

        $plan     = date('H:i', strtotime($r["plan"]));
        $end_time = date('H:i', strtotime($r["end_time"]));

        $stmt2->bindValue(':LOG_ID',    $LOG_ID,      PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt2->bindValue(':step_ID',   $r["step_ID"],PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt2->bindValue(':action',    $r["action"], PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt2->bindValue(':time',      $r["time"],   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt2->bindValue(':plan',      $plan,        PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt2->bindValue(':step',      $r["step"],   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt2->bindValue(':end_time',  $end_time,    PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $status2 = $stmt2->execute();//SQL実行
        //４．データ登録処理後
        if($status2==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            echo "Error: ";
            echo $error = $stmt2->errorInfo().$sql.'<br>';
        }
    }
    redirect("log.php");
}
?>