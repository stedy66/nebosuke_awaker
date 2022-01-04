<?php
session_start();
//GET送信されたMR_IDを取得
$MR_ID=$_GET["MR_ID"];
include("funcs.php");
$pdo = db_conn();

//２．ルーティンパッケージ抽出SQL作成
$stmt = $pdo->prepare("SELECT * FROM table1_1 WHERE MR_ID=:MR_ID");
$stmt->bindValue(":MR_ID", $MR_ID, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ作成
if($status==false) {
    sql_error($stmt);
}else{
    $package = $stmt->fetch();
}

if ($package["IMG_URL"]=="") {
  $bg_url="upload/default_bg.jpg";
}
else {
  $bg_url=$package["IMG_URL"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/mrdetail.css">
  <title>Document</title>
</head>

<body>
  <header>ルーティン</header>
  <!-- 画像、ルーティン -->
  <div style="text-align: center; background-image: url('<?=$bg_url?>');" id="routine_name_bgi">
    <p id="routine_name"><?=$package["ROUTINE_NAME"]?></p>
  </div>
  <!-- テーブル -->
  <?php
  //２．ルーティンパッケージ抽出SQL作成
  $stmt = $pdo->prepare("SELECT * FROM table1_3 LEFT JOIN table1_2 ON table1_3.STEP_ID=table1_2.STEP_ID WHERE table1_3.MR_ID=:MR_ID ORDER BY table1_3.SEQUENCE");
  $stmt->bindValue(":MR_ID", $MR_ID, PDO::PARAM_INT);
  $status = $stmt->execute();

  //３．データ作成
  if($status==false) {
      sql_error($stmt);
  }else{
    while( $res = $stmt->fetch(PDO::FETCH_ASSOC)){
      $view = '<div class="step">' ;
      $view .= "<p class='step_seq'>".$res["SEQUENCE"]."</p>";
      $view .= "<p class='step_name'>".$res["STEP_NAME"]."</p>";
      $view .= "<p class='step_period'>".$res["PERIOD"]."min</p>";
      $view .= "<p text-align: left;' class='step_description'>".$res["DESCRIPTION"]."</p>";
      $view .= '</div>';
      echo $view;
      }
    }
    ?>
    <!-- コメント -->
    <?php
      //nl2br関数はphpの改行コードをhtmlの改行タグに変換してくれる関数
      echo '<p style="width: 70%; margin-left: auto; margin-right: auto;">'.nl2br($package["DESCRIPTION"]).'</p>';
    ?>
    <!-- YouTubeリンク（矢部さんのレイアウトには現れないので、要確認） -->
    <?php
    if ($package["YOUTUBE"]!="") {
      echo '<p style="width: 70%; margin-left: auto; margin-right: auto;"><a href="'.$package["YOUTUBE"].'">YouTubeへリンク<a/></p>';
    }
    ?>
    <!-- ログイン状態なら「ダウンロードする・実行する」ボタンを追加 -->
    <?php
      if(isset($_SESSION["chk_ssid"]) && $_SESSION["chk_ssid"]==session_id() && $_SESSION["USER_ID"]!=$package["USER_ID"]){
        //2. 登録済みのUSER_IDとの重複チェック
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM table2 WHERE USER_ID=:USER_ID AND MR_ID=:MR_ID"); 
        $stmt->bindValue(':USER_ID', $_SESSION["USER_ID"], PDO::PARAM_STR);
        $stmt->bindValue(':MR_ID', $MR_ID, PDO::PARAM_STR);
        $status = $stmt->execute();
        //3. SQL実行時にエラーがある場合STOP
        if($status==false){
          sql_error($stmt);
        }
        $count = $stmt->fetchColumn();
        $view="";
        if ($count>0) {
          $view.='<p style="width: 70%; margin-left: auto; margin-right: auto;">';
          $view.='「ダウンロード済みのモーニングルーティンです」';
          $view.='</p>';
          $view.='<p style="width: 70%; margin-left: auto; margin-right: auto;">';
          $view.='<a href="delete_from_mmr.php?MR_ID='.$MR_ID.'">';
          $view.='「削除する（my morning routineから削除します）」';
          $view.='</a>';
          $view.='</p>';
        } else {
          $view.='<p style="width: 70%; margin-left: auto; margin-right: auto;">';
          $view.='<a href="download.php?MR_ID='.$MR_ID.'">';
          $view.='「ダウンロードする・実行する」';
          $view.='</a>';
          $view.='</p>';
        }
        echo $view;
      } else if (isset($_SESSION["chk_ssid"]) && $_SESSION["chk_ssid"]==session_id() && $_SESSION["USER_ID"]==$package["USER_ID"]) {
        $view="";
        $view.='<p style="width: 70%; margin-left: auto; margin-right: auto;">';
        $view.='<a href="edit.php".php>';
        $view.='「編集する」';
        $view.='</a>';
        $view.='</p>';
        echo $view;
      }
      echo '<p style="width: 70%; margin-left: auto; margin-right: auto;"><a href="top2.php".php>トップに戻る</a></p>';
    ?>
</body>
</html>