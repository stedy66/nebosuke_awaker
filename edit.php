<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();

//２．MRPステップ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM table1_2");
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if ($status == false) {
  sql_error($stmt);
}

//4. 自分で作成したモーニングルーティンかを判定
$stmt2 = $pdo->prepare("SELECT * FROM table1_1 WHERE MR_ID=:MR_ID");
$stmt2->bindValue(":MR_ID", $_GET["MR_ID"], PDO::PARAM_INT);
$status = $stmt2->execute();
if ($status == false) {
  sql_error($stmt2);
} else {
  $package = $stmt2->fetch();
  if ($package["USER_ID"] != $_SESSION["USER_ID"]) {
    echo '<p>編集可能なMRではありません。</p>';
    echo '<p><a href="top2.php".php>トップに戻る</a></p>';
    exit();
  }
}

//5. ステップを取得
$stmt3 = $pdo->prepare("SELECT COUNT(*) FROM table1_3 WHERE MR_ID=:MR_ID");
$stmt3->bindValue(":MR_ID", $_GET["MR_ID"], PDO::PARAM_INT);
$status = $stmt3->execute();
if ($status == false) {
  sql_error($stmt3);
}
$count = $stmt3->fetchColumn();

$stmt4 = $pdo->prepare("SELECT * FROM table1_3 WHERE MR_ID=:MR_ID");
$stmt4->bindValue(":MR_ID", $_GET["MR_ID"], PDO::PARAM_INT);
$status = $stmt4->execute();
if ($status == false) {
  sql_error($stmt4);
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
  <header>マイルーティン編集</header>
  <?php
  if (!isset($_SESSION['icon'])) {
  } else {
    echo '<img style="width:50px;" src="upload/' . $_SESSION['icon'] . '" alt="">';
  }
  ?>
  <script>
    function confirm_test() {
      var select = confirm("本当に登録しますか？\n「OK」で登録\n「キャンセル」で登録中止");
      return select;
    }
  </script>

  <form action="edit_act.php?MR_ID=<?= $_GET["MR_ID"] ?>" method="POST" onsubmit="return confirm_test()">
    <div id="routine_name_bgi"><input type="text" name="ROUTINE_NAME" placeholder="モーニングルーティン名を設定してください" id="routine_name" value="<?= $package['ROUTINE_NAME'] ?>" /></div>
    <table id="table">
      <tr>
        <td id="lu" style="background-color: #ffc900; border-right: 2px solid #fff;">Action</td>
        <td style="background-color: #ffc900; border-right: 2px solid #fff;">Description</td>
        <td id="ru" style="background-color: #ffc900;">Time</td>
      </tr>
      <?php
      $option = "";
      while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $option .= '<option value=' . $r["STEP_ID"] . '>' . $r["STEP_NAME"] . '</option>';
      }

      $i = 1;

      while ($r = $stmt4->fetch(PDO::FETCH_ASSOC)) {
        $option2 = "";
        $stmt = $pdo->prepare("SELECT * FROM table1_2");
        $status = $stmt->execute();
        while ($r2 = $stmt->fetch(PDO::FETCH_ASSOC)) {
          if ($r["STEP_ID"] == $r2["STEP_ID"]) {
            $option2 .= '<option value=' . $r2["STEP_ID"] . ' selected>' . $r2["STEP_NAME"] . '</option>';
          } else {
            $option2 .= '<option value=' . $r2["STEP_ID"] . '>' . $r2["STEP_NAME"] . '</option>';
          }
        }
        $view = "";
        $view .= "<tr>";
        if ($i == $count) {
          $view .= "<td class='action' id='ld'>";
        } else {
          $view .= "<td class='action'>";
        }
        $view .= "<select name='STEP_ID" . $i . "'>";
        $view .= "<option value=0>--選択してください--</option>";
        $view .= $option2;
        $view .= "</select>";
        $view .= "</td>";
        $view .= "<td class='description'><textarea name='DESCRIPTION" . $i . "'>" . $r["DESCRIPTION"] . "</textarea></td>";
        if ($i == $count) {
          $view .= "<td class='time' id='rd'><input type='number' step=1 min=0 value=" . $r["PERIOD"] . " name='PERIOD" . $i . "' style='width: 50px;'/>min</td>";
        } else {
          $view .= "<td class='time'><input type='number' step=1 min=0 value=" . $r["PERIOD"] . " name='PERIOD" . $i . "' style='width: 50px;'/>min</td>";
        }
        $view .= "</tr>";
        echo $view;
        $i++;
      }
      ?>
    </table>
    <div id="plus-bg">
      <p id="plus">+</p>
      <p style="margin-left: 20px;">Actionを追加する</p>
    </div>
    <div id="comment-bg"><textarea name="DESCRIPTION" placeholder="コメント（任意）" id="comment"><?= $package['DESCRIPTION'] ?></textarea></div>
    <div id="youtube-bg"><input type="url" name="YOUTUBE" placeholder="YouTube動画のurlを入れてください（任意）" id="youtube" value="<?= $package['YOUTUBE'] ?>" /></div>
    <div id="share-bg">
      <p>みんなにシェア</p><input type="checkbox" name="SHARED" <?php if ($package["SHARED"] == 1) {
                                                            echo "checked";
                                                          } ?>>
    </div>
    <div class="register-bg"><input type="submit" value="登録" class="register" /></div>
  </form>
  <a href="delete.php?MR_ID=<?= $_GET["MR_ID"] ?>">
    <div class="register-bg"><button class="delete">削除する</button></div>
  </a>
  <p style="margin: 20px auto 30px auto; width: 90%;"><a href="top2.php" .php>トップに戻る</a></p>

  <script>
    i = <?php echo $i ?>;
    $("#plus").on("click", function() {
      $('#ld').removeAttr('id');
      $('#rd').removeAttr('id');
      <?php
      $view = "<tr>";
      $view .= "<td class='action' id='ld'>";
      $view .= "<select name='STEP_ID";
      $view2 = "'>";
      $view2 .= "<option value=0 selected>--選択してください--</option>";
      $view2 .= $option;
      $view2 .= "</select>";
      $view2 .= "</td>";
      $view2 .= "<td class='description'><textarea name='DESCRIPTION";
      $view3 = "'></textarea></td>";
      $view3 .= "<td class='time' id='rd'><input type='number' step=1 min=0 value=0 name='PERIOD";
      $view4 = "' style='width: 50px;'/>min</td>";
      $view4 .= "</tr>";
      ?>
      $("#table").append("<?= $view ?>" + i + "<?= $view2 ?>" + i + "<?= $view3 ?>" + i + "<?= $view4 ?>");
      i++;
    });
  </script>
</body>

</html>