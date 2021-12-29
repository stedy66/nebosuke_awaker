<?php
session_start();
//GET送信されたMR_IDを取得
// $MR_ID=$_GET["MR_ID"];
$MR_ID=4; //テスト
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
  <title>Document</title>
</head>
<body>
  <!-- 画像 -->
  <div style="text-align: center">
    <img src="<?=$bg_url?>" alt="<?=$package["ROUTINE_NAME"]?>" title="<?=$package["ROUTINE_NAME"]?>" style="width: 70%;">
</div>
  <!-- ルーティン名 -->
  <p style="width: 70%; text-align: center; margin-left: auto; margin-right: auto;"><?=$package["ROUTINE_NAME"]?></p>
  <!-- テーブル -->
  <table style="width: 70%; text-align: center; margin-left: auto; margin-right: auto;" cellspacing="0">
      <tr>
        <td style="background:yellow;border:1px solid;">ステップ数</td>
        <td style="background:yellow;border:1px solid;">ステップ名</td>
        <td style="background:yellow;border:1px solid;">時間</td>
        <td style="background:yellow;border:1px solid;">説明</td>
       </tr>
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
            $view = '<tr>' ;
            $view .= "<td style='background:#ffffe0;border:1px solid;'>".$res["SEQUENCE"]."</td>";
            $view .= "<td style='background:#ffffe0;border:1px solid;'>".$res["STEP_NAME"]."</td>";
            $view .= "<td style='background:#ffffe0;border:1px solid;'>".$res["PERIOD"]."</td>";
            $view .= "<td style='background:#ffffe0;border:1px solid; text-align: left;'>".$res["DESCRIPTION"]."</td>";
            $view .= '<tr>';
            echo $view;
            }
        }
        ?>
    </table>
    <!-- コメント -->
    <?php
      //nl2br関数はphpの改行コードをhtmlの改行タグに変換してくれる関数
      echo '<p style="width: 70%; margin-left: auto; margin-right: auto;">'.nl2br($package["DESCRIPTION"]).'</p>';
    ?>
    <!-- YouTubeリンク（矢部さんのにレイアウトには現れないので、要確認） -->
    <?php
      echo '<p style="width: 70%; margin-left: auto; margin-right: auto;"><a href="'.$package["YOUTUBE"].'">YouTubeへリンク<a/></p>';
    ?>
    <!-- ログイン状態なら「ダウンロードする・実行する」ボタンを追加 -->
    <?php
      if(isset($_SESSION["chk_ssid"]) && $_SESSION["chk_ssid"]==session_id()){
        $view="";
        $view.='<p>';
        $view.='<a href="userreg_act.php?USER_ID='. $_SESSION["USER_ID"].'&MR_ID='.$MR_ID.'">';
        $view.='「ダウンロードする・実行する」';
        $view.='</a>';
        $view.='</p>';
        echo $view;
      }
    ?>
</body>
</html>