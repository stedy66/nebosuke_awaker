<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();

//２．ユーザー取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM table4 WHERE USER_ID=:USER_ID");
$stmt->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_STR);
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if ($status == false) {
  sql_error($stmt);
}

//4. ユーザーデータを抽出
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/mrplist.css">
  <link rel="stylesheet" href="css/top2style-copy.css">
  <title>Document</title>
</head>

<body>
  <header>
    <div class="home">マイモーニングルーティーン</div>
    <!-- <div class="nav_item"><a href="#">ログアウト</a></div> -->
    <div class="hamburger-menu">
      <input type="checkbox" id="menu-btn-check">
      <label for="menu-btn-check" class="menu-btn"><span></span></label>
      <!--ここからメニュー-->
      <div class="menu-content">
        <ul>
          <li>
            <a href="logout.php">ログアウト</a>
          <li>
            <a href="mrpreg.php">モーニングルーティン新規登録</a>
          </li>
          <li>
            <a href="mymrp.php">マイモーニングルーティン</a>
          </li>
          <li>
            <a href="sharedmrp.php">みんなのモーニングルーティン</a>
          </li>
          <li>
            <a href="log.php">私の記録</a>
          </li>
          <li>
            <a href="logshare.php">みんなの記録</a>
          </li>
        </ul>
      </div>
      <!--ここまでメニュー-->
    </div>
  </header>
  <p style="margin: 20px auto 30px auto; width: 600px;"><a href="top2.php" .php>トップに戻る</a></p>
  <?php
  //My MRの一覧取得
  $stmt = $pdo->prepare("SELECT * FROM table2 LEFT JOIN table1_1 ON table2.MR_ID=table1_1.MR_ID WHERE table2.USER_ID=:USER_ID");
  $stmt->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_STR);
  $status = $stmt->execute();
  if ($status == false) {
    sql_error($stmt);
  } else {
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $view = '<a href="mrdetail.php?MR_ID=' . $r["MR_ID"] . '">';
      $view .= '';
      $view .= '<div class="mrp" style="background-image: url(upload/default_bg.jpg);">';
      $view .= '<img src="img/ネボスケロゴ_文字黒.jpg" class="logo">';
      $view .= '<p>' . $r["ROUTINE_NAME"] . '<p>';
      $view .= '</div>';
      $view .= '</a>';
      echo $view;
    }
  }
  ?>
</body>

</html>