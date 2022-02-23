<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();
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
    <div class="home">みんなのmorning routine一覧</div>
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
          <li>
            <a href="top2.php">TOP</a>
          </li>
        </ul>
      </div>
      <!--ここまでメニュー-->
    </div>
  </header>
  <?php
  if (!isset($_SESSION['icon'])) {
  } else {
    echo '<img style="width:50px;" src="upload/' . $_SESSION['icon'] . '" alt="">';
  }
  ?>
  <?php
  //シェアされたMRの一覧取得
  $stmt = $pdo->prepare("SELECT * FROM table1_1 WHERE SHARED=1 ORDER BY DOWNLOAD_NUM DESC");
  $status = $stmt->execute();
  if ($status == false) {
    sql_error($stmt);
  } else {
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $view = '';
      $view .= '<a href="logshere2.php?MR_ID=' . $r["MR_ID"] . ' & USER_ID=' . $r["USER_ID"] . '">';
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