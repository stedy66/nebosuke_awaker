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
if($status==false){
  sql_error($stmt);
}

//4. 抽出データ数を取得
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="css/top2style.css">
  <link rel="stylesheet" href="css/slick.css">
  <link rel="stylesheet" href="css/slick-theme.css">
</head>
<body>
   
  <header>
    <img src="img/ネボスケロゴ_文字黒.jpg" class="logo">
    <div class="home" >ホーム</div>   
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
  <?php
    if (isset($_SESSION["download"]) && $_SESSION["download"]==1) {
      echo "<p>ダウンロードしました。</p>";
      $_SESSION["download"]=0;
    }
    if (isset($_SESSION["delete"]) && $_SESSION["delete"]==1) {
      echo "<p>my morning routineから削除しました。</p>";
      $_SESSION["delete"]=0;
    }
  ?>
  <p style="margin: 20px auto 30px auto; width: 600px;">こんにちは、<?=$user["USER_NAME"].'さん'?></p>
  <!-- <p style="margin: 20px auto 30px auto; width: 600px;"><a href="mrpreg.php">新モーニングルーティンを作成する</a></p> -->
  <p style="margin: 20px auto 30px auto; width: 600px; font-weight: 700; font-size:larger;">マイモーニングルーティン　<a style="text-decoration: none;" href="mymrp.php">すべて表示</a></p>
  <?php
  //My MRの一覧取得
  $stmt = $pdo->prepare("SELECT * FROM table2 LEFT JOIN table1_1 ON table2.MR_ID=table1_1.MR_ID WHERE table2.USER_ID=:USER_ID");
  $stmt->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_STR);
  $status = $stmt->execute();
  
  if($status==false){
    sql_error($stmt);
  } else {
    $i=0;
    echo '<div class="multiple-items">';
    while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){
      if ($i>=10) {
        break;
      } 
      $view='<a  style="text-decoration: none;" href="mrdetail.php?MR_ID='.$r["MR_ID"].'">';
      $view.='<div>';
      if ($i%3==0) {
        $view.='<div class="mrp" style="background-image: url(upload/default_bg.jpg)">';
      } else if ($i%3==1) {
        $view.='<div class="mrp" style="background-image: url(img/pexels-martin-péchy-922100.jpg)">';
      } else {
        $view.='<div class="mrp" style="background-image: url(img/pexels-pavel-danilyuk-6443483.jpg)">';
      }
      // $view.='<img src="img/ネボスケロゴ_文字黒.jpg" class="logo">';
      // $view.='</div>';
      // $view.='</a>';
      // echo $view;
      // $view2='<div class="mrp_name">';
      // $view2.='<a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">';
      $view.='</div>';
      $view.='<p class="mrp_name">'.$r["ROUTINE_NAME"].'<p>';
      $view.='</div>';
      $view.='</a>';
      echo $view;

      // echo '<p style="margin: 20px auto 30px auto; width: 600px;"><a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">'.$r["ROUTINE_NAME"].'</a><p>';
      $i+=1;

    }
    echo '</div>';
  }
  ?>
  <p style="margin: 20px auto 30px auto; width: 600px; font-weight: 700; font-size:larger;">みんなのモーニングルーティン　<a style="text-decoration: none;" href="sharedmrp.php">すべて表示</a></p>
  <?php
  //みんなのMRの一覧取得
  $stmt = $pdo->prepare("SELECT * FROM table1_1 WHERE SHARED=1 ORDER BY DOWNLOAD_NUM DESC");
  $status = $stmt->execute();
  if($status==false){
    sql_error($stmt);
  } else {
    $i=0;
    echo '<div class="multiple-items">';
    while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){
      if ($i>=10) {
        break;
      } 
      $view='';
      $view.='<a style="text-decoration: none;" href="mrdetail.php?MR_ID='.$r["MR_ID"].'">';
      $view.='<div>';
      if ($i%3==0) {
        $view.='<div class="mrp" style="background-image: url(img/pexels-engin-akyurt-2299028.jpg)">';
      } else if ($i%3==1) {
        $view.='<div class="mrp" style="background-image: url(img/pexels-tamba-budiarsana-979247.jpg)">';
      } else {
        $view.='<div class="mrp" style="background-image: url(img/pexels-thought-catalog-904616.jpg)">';
      }
      // $view.='<img src="img/ネボスケロゴ_文字黒.jpg" class="logo">';
      $view.='</div>';
      // $view.='<a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">';
      $view.='<p class="mrp_name">'.$r["ROUTINE_NAME"].'</p>';
      $view.='</div>';
      $view.='</a>';
      echo $view;

      // echo '<p style="margin: 20px auto 30px auto; width: 600px;"><a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">'.$r["ROUTINE_NAME"].'</a><p>';
      $i+=1;
    }
    echo '</div>';
  }
  ?>
  <script src="js/jquery-3.6.0.min.js"></script>
  <script src="js/slick.min.js"></script>
  <script src="js/main.js"></script>
</body>
  
</html>