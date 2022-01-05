<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();

//２．MRPステップ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM table2 LEFT JOIN table1_1 ON table2.MR_ID=table1_1.MR_ID WHERE table2.USER_ID=:USER_ID");
$stmt->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_STR);
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if($status==false){
  sql_error($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/mrpreg.css">
  <title>Document</title>
  <script src="js/jquery-2.1.3.min.js"></script>
</head>

<body>
<header>翌朝のルーティンの登録</header>

<script>
function confirm_test() {
    var select = confirm("本当に登録しますか？\n「OK」で登録\n「キャンセル」で登録中止");
    return select;
}
</script>

<form action="nmrpreg_act.php" method="POST" onsubmit="return confirm_test()">
  
  <div style='display: flex; align-items: center;' class='text'><input type="date" name = "DATE"><p>のモーニングルーティンを登録する。</p></div>
  <div class="register-bg">
    <select name="MR_ID">
      <option selected>--実践するモーニングルーティンを選択してください--</option>
      <?php
        while( $res = $stmt->fetch(PDO::FETCH_ASSOC)){
          echo '<option value="'.$res["MR_ID"].'">'.$res["ROUTINE_NAME"].'</option>';
        }
      ?>
    </select>
  </div>
  <p class='text'>モーニングルーティンの開始時間を入力してください。</p>
  <div class="register-bg"><input type="time" name='START_TIME'></div>
  <div class="register-bg"><input type="submit" value="登録" class="register"/></div>
</form>
<p style="margin: 20px auto 30px auto; width: 90%;"><a href="top2.php".php>トップに戻る</a></p>

</body>
</html>