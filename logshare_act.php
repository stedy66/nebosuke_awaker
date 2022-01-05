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
$comment    = $_POST["comment"];
$evaluation = $_POST["evaluation"];

echo $comment;
echo $evaluation;

//受信データ登録SQL作成
//prepare("")の中にはmysqlのSQLで入力したINSERT文を入れて修正すれば良いイメージ
$stmt1 = $pdo->prepare("SELECT* FROM table3_test");
$status1 = $stmt1->execute();

//送信データ登録SQL作成（テンプレート）
$sql = "INSERT INTO table5_test(user_id,date,MR_ID,step_ID,action,time,plan,step,end_time,comment,evaluation)VALUES(:user_id, :date, :MR_ID, :step_ID, :action, :time, :plan, :step, :end_time, :comment, :evaluation)";
$stmt = $pdo->prepare($sql);

if($status1==false) {
    //SQLエラーの場合
    sql_error($stmt1);
}else{
    //SQL成功の場合
    while( $r = $stmt1->fetch(PDO::FETCH_ASSOC)){ //データ取得数分繰り返す

        $plan     = date('H:i', strtotime($r["plan"]));
        $end_time = date('H:i', strtotime($r["end_time"]));

        $stmt->bindValue(':user_id',   $r["user_id"],PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':date',      $r["date"],   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':MR_ID',     $r["MR_ID"],  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':step_ID',   $r["step_ID"],PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':action',    $r["action"], PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':time',      $r["time"],   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':plan',      $plan,        PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':step',      $r["step"],   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':end_time',  $end_time,    PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':comment',   $comment,     PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':evaluation',$evaluation,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $status = $stmt->execute();//SQL実行
        //４．データ登録処理後
        if($status==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            echo "Error: ";
            echo $error = $stmt->errorInfo().$sql.'<br>';
        }
    }
    redirect("log.php");
}
?>