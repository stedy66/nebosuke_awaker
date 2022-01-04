<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();

//２．ユーザー取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM table1_2");
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
  <link rel="stylesheet" href="css/main.css">
  <title>Document</title>
  <script src="js/jquery-2.1.3.min.js"></script>
</head>

<body>
<header>マイルーティン入力</header>
<form action="mrreg_act.php" method="POST" onsubmit="return confirm_test()">
  <input type="text" name="ROUTINE_NAME" placeholder="モーニングルーティン名を設定してください"/>
  <table id="table">
    <tr>
      <td>Action</td>
      <td>Description</td>
      <td>Time</td>
    </tr>
    <?php
    $option="";
    while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ 
      $option.='<option value='.$r["STEP_ID"].'>'.$r["STEP_NAME"].'</option>';
    }
    for ($i=1; $i<4; $i++) {
      $view="";
      $view.="<tr>";
      $view.="<td>";
      $view.="<select name='STEP_ID".$i."'>";
      $view.="<option value=0 selected>--選択してください--</option>";
      $view.=$option;
      $view.="</select>";
      $view.="</td>";
      $view.="<td><input type='text' name='DESCRIPTION".$i."'/></td>";
      $view.="<td><input type='number' step=1 min=0 value=0 name='PERIOD".$i."'/>min</td>";
      $view.="</tr>";
      echo $view;
    }
    ?>
  </table>
  <p id="plus">+</p>
  <input type="text" name="DESCRIPTION" placeholder="コメント（任意）"/>
  <input type="text" name="YOUTUBE" placeholder="YouTube動画のリンクを入れてください（任意）"/>
  <p>みんなにシェア<input type="checkbox" name="SHARED"></p>
  <input type="submit" value="登録" />
</form>

<script>
  $("#plus").on("click", function() {
    <?php
      $view="";
      $view.="<tr>";
      $view.="<td>";
      $view.="<select name='STEP_ID".$i."'>";
      $view.="<option value=0 selected>--選択してください--</option>";
      $view.=$option;
      $view.="</select>";
      $view.="</td>";
      $view.="<td><input type='text' name='DESCRIPTION".$i."'/></td>";
      $view.="<td><input type='number' step=1 min=0 value=0 name='PERIOD".$i."'/>min</td>";
      $view.="</tr>";
      $i++;
    ?>
    $("#table").append("<?=$view?>");
  });
</script>
</body>
</html>