<?php
session_start();
//GET送信されたMR_IDを取得
$MR_ID = $_GET["MR_ID"];
include("funcs.php");
sschk();
$pdo = db_conn();

//1．ルーティンパッケージ抽出SQL作成
$stmt = $pdo->prepare("SELECT * FROM table1_1 WHERE MR_ID=:MR_ID");
$stmt->bindValue(":MR_ID", $MR_ID, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ作成
if ($status == false) {
  sql_error($stmt);
} else {
  $package = $stmt->fetch(pdo::FETCH_ASSOC);
}


if ($package["IMG_URL"] == "") {
  $bg_url = "upload/default_bg.jpg";
} else {
  $bg_url = $package["IMG_URL"];
}

//このルーティーンを登録したユーザーの情報を取得（プロフィール画面への遷移用）
$user_id = $package['USER_ID'];

$user_stmt = $pdo->prepare("SELECT * FROM table4 WHERE USER_ID=:USER_ID");
$user_stmt->bindValue(":USER_ID", $user_id, PDO::PARAM_STR);
$user_status = $user_stmt->execute();

if ($user_status == false) {
  sql_error($user_status);
} else {
  $user = $user_stmt->fetch(pdo::FETCH_ASSOC);
}

//フォーロー機能のための記述
//ログインしているユーザーのUSER_ID
$current_user = $_SESSION['USER_ID'];
//表示されているルーティーンを作成したユーザーのUSRE_ID
$profile_user = $package['USER_ID'];

//フォロー数の取得
// follow_table からこのルーティーンを登録したユーザーがフォローしている数を取得
$stmt_follow = $pdo->prepare('SELECT COUNT(*) FROM follow_table WHERE follow_id=:follow_id;');
$stmt_follow->bindValue(':follow_id', $profile_user, PDO::PARAM_STR);
$status_follow = $stmt_follow->execute();
$result_follow = $stmt_follow->fetch(PDO::FETCH_ASSOC);


// follow_table からこのルーティーンを登録したユーザーがフォローされている人数を取得
$stmt_followed = $pdo->prepare('SELECT COUNT(*) FROM follow_table WHERE followed_id=:followed_id;');
$stmt_followed->bindValue(':followed_id', $profile_user, PDO::PARAM_STR);
$status_followed = $stmt_followed->execute();
$result_followed = $stmt_followed->fetch(PDO::FETCH_ASSOC);

//いいねの数を取得
$stmt_like = $pdo->prepare('SELECT COUNT(*) FROM like_table WHERE MR_ID=:MR_ID;');
$stmt_like->bindValue(':MR_ID', $MR_ID, PDO::PARAM_STR);
$status_like = $stmt_like->execute();
$result_like = $stmt_like->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/mrdetail.css">
  <link rel="stylesheet" href="css/top2style-copy.css">
  <title>Document</title>
</head>

<body>
  <header>
    <div class="home">ルーティーン一覧</div>
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
            <a href="logsharelist.php">みんなの記録</a>
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
  // if (!isset($_SESSION['icon'])) {
  // } else {
  //   echo '<img style="width:50px;" src="upload/' . $_SESSION['icon'] . '" alt="">';
  // }
  ?>
  <!-- 画像、ルーティン -->
  <div style="text-align: center; background-image: url('<?= $bg_url ?>');" id="routine_name_bgi">
    <p id="routine_name"><?= $package["ROUTINE_NAME"] ?></p>
  </div>

  
  <!-- ログインしているユーザーと閲覧しているルーティーンを作成したユーザーが異なる場合ボタンを表示 -->
  <?php if ($current_user != $profile_user) : ?>
    <!-- プロフィール画面への遷移用
    SQLで挿入したデータで無ければ正常に動くと思う -->
    <div id="profile">
      <div class="profile">
        <div class="profile_icon">
            <a href="user_detail.php?USER_ID=<?=$user["USER_ID"]?>">
            <img class="icon" src="upload/<?= $user['icon'] ?> " alt="">
          </div>
          <div class="profile_name"><?= $user["USER_NAME"] ?></div></a>
      </div>
      <div class="follow">
        <p><?= $result_follow["COUNT(*)"] ?> 　フォロー</p>
        <p><?= $result_followed["COUNT(*)"] ?> 　フォロワー</p>
      </div>
      <div class="follow_form">
        <?php if (check_follow($current_user, $profile_user)) : ?>
          <form action="follow_delete.php?MR_ID=<?php echo $MR_ID ?>" method="post">
            <input type="hidden" name="current_user_id" value="<?= $current_user ?>">
            <input type="hidden" name="follow_user_id" value="<?= $profile_user ?>">
            <input type="submit" value="フォロー中" class="btn">
          </form>
        <?php else : ?>
          <form action="follow.php?MR_ID=<?php echo $MR_ID ?>" method="post">
            <input type="hidden" name="current_user_id" value="<?= $current_user ?>">
            <input type="hidden" name="follow_user_id" value="<?= $profile_user ?>">
            <input type="submit" value="フォロー" class="btn">
          </form>
        <?php endif; ?>
      </div>
    </div>
    <div id="iine">
      <div class="iine">
        <p><?= $result_like["COUNT(*)"] ?> いいね！</p>
      </div>
      <div class="iine_form">
        <?php if ($current_user != $profile_user) : ?>
          <?php if (check_like($current_user, $MR_ID)) : ?>
            <form action="like_delete.php?MR_ID=<?php echo $MR_ID ?>" method="post">
              <input type="hidden" name="like_user" value="<?= $current_user ?>">
              <input type="submit" value="いいね済み" class="btn">
            </form>
          <?php else : ?>
            <form action="like.php?MR_ID=<?php echo $MR_ID ?>" method="post">
              <input type="hidden" name="like_user" value="<?= $current_user ?>">
              <input type="submit" value="いいね！" class="btn">
            </form>
          <?php endif; ?>

        <?php endif; ?>
      </div>

    </div>

    <!-- <a href="user_detail.php?USER_ID=<?=$user["USER_ID"]?>">
      <p><?= $user["USER_NAME"] ?>のプロフィール</p>
    </a> -->
    <!-- <p><?= $result_follow["COUNT(*)"] ?> フォロー</p>
    <p><?= $result_followed["COUNT(*)"] ?> フォロワー</p>
    <?php if (check_follow($current_user, $profile_user)) : ?>
      <form action="follow_delete.php?MR_ID=<?php echo $MR_ID ?>" method="post">
        <input type="hidden" name="current_user_id" value="<?= $current_user ?>">
        <input type="hidden" name="follow_user_id" value="<?= $profile_user ?>">
        <input type="submit" value="フォロー中">
      </form>
    <?php else : ?>
      <form action="follow.php?MR_ID=<?php echo $MR_ID ?>" method="post">
        <input type="hidden" name="current_user_id" value="<?= $current_user ?>">
        <input type="hidden" name="follow_user_id" value="<?= $profile_user ?>">
        <input type="submit" value="フォロー">
      </form>
    <?php endif; ?> -->

    <!-- ログインしているユーザーと閲覧しているルーティーンを作成したユーザーが異なる場合ボタンを表示 -->
    <!-- <p><?= $result_like["COUNT(*)"] ?> いいね！</p>
    <?php if ($current_user != $profile_user) : ?>
      <?php if (check_like($current_user, $MR_ID)) : ?>
        <form action="like_delete.php?MR_ID=<?php echo $MR_ID ?>" method="post">
          <input type="hidden" name="like_user" value="<?= $current_user ?>">
          <input type="submit" value="いいね済み">
        </form>
      <?php else : ?>
        <form action="like.php?MR_ID=<?php echo $MR_ID ?>" method="post">
          <input type="hidden" name="like_user" value="<?= $current_user ?>">
          <input type="submit" value="いいね！">
        </form>
      <?php endif; ?>

    <?php endif; ?> -->

  <?php endif; ?>


  <!-- テーブル -->
  <?php
  //２．ルーティンパッケージ抽出SQL作成
  $stmt = $pdo->prepare("SELECT * FROM table1_3 LEFT JOIN table1_2 ON table1_3.STEP_ID=table1_2.STEP_ID WHERE table1_3.MR_ID=:MR_ID ORDER BY table1_3.SEQUENCE");
  $stmt->bindValue(":MR_ID", $MR_ID, PDO::PARAM_INT);
  $status = $stmt->execute();

  //３．データ作成
  if ($status == false) {
    sql_error($stmt);
  } else {
    while ($res = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $view = '<div class="step">';
      $view .= "<p class='step_seq'>" . $res["SEQUENCE"] . "</p>";
      $view .= "<p class='step_name'>" . $res["STEP_NAME"] . "</p>";
      $view .= "<p class='step_period'>" . $res["PERIOD"] . "min</p>";
      $view .= "<p text-align: left;' class='step_description'>" . $res["DESCRIPTION"] . "</p>";
      $view .= '</div>';
      echo $view;
    }
  }
  ?>
  <!-- コメント -->
  <?php
  if ($package["DESCRIPTION"] != "") {
    //nl2br関数はphpの改行コードをhtmlの改行タグに変換してくれる関数
    echo '<p id="comment">' . nl2br($package["DESCRIPTION"]) . '</p>';
  }
  ?>
  <!-- YouTubeリンク -->
  <?php
  if ($package["YOUTUBE"] != "") {
    echo '<p style="margin: 20px auto 30px auto; width: 90%;"><a href="' . $package["YOUTUBE"] . '">YouTubeへリンク<a/></p>';
  }
  ?>
  <!-- ログイン状態なら「ダウンロードする・実行する」ボタンを追加 -->
  <?php
  if (isset($_SESSION["chk_ssid"]) && $_SESSION["chk_ssid"] == session_id() && $_SESSION["USER_ID"] != $package["USER_ID"]) {
    //2. 登録済みのUSER_IDとの重複チェック
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM table2 WHERE USER_ID=:USER_ID AND MR_ID=:MR_ID");
    $stmt->bindValue(':USER_ID', $_SESSION["USER_ID"], PDO::PARAM_STR);
    $stmt->bindValue(':MR_ID', $MR_ID, PDO::PARAM_STR);
    $status = $stmt->execute();
    //3. SQL実行時にエラーがある場合STOP
    if ($status == false) {
      sql_error($stmt);
    }
    $count = $stmt->fetchColumn();

    $view = "";
    if ($count > 0) {
      $view .= '<p style="margin: 20px auto 30px auto; width: 90%;">';
      $view .= '<a href="delete_from_mmr.php?MR_ID=' . $MR_ID . '">';
      $view .= '削除する（my morning routineから削除します）';
      $view .= '</a>';
      $view .= '　ダウンロード済みのモーニングルーティンです';
      $view .= '</p>';
      echo $view;
    } else {
      $view .= '<p style="margin: 20px auto 30px auto; width: 90%;">';
      $view .= '<a href="download.php?MR_ID=' . $MR_ID . '">';
      $view .= 'ダウンロードする';
      $view .= '</a>';
      $view .= '</p>';
      echo $view;
    }
  } else if (isset($_SESSION["chk_ssid"]) && $_SESSION["chk_ssid"] == session_id() && $_SESSION["USER_ID"] == $package["USER_ID"]) {
    $view = "";
    if (isset($_SESSION["chk_ssid"]) && $_SESSION["chk_ssid"] == session_id() && $_SESSION["USER_ID"] != $package["USER_ID"]) {
      $view .= '<p style="margin: 20px auto 30px auto; width: 90%;">';
      $view .= '<a href="delete_from_mmr.php?MR_ID=' . $MR_ID . '">';
      $view .= '削除する（my morning routineから削除します）';
      $view .= '</a>';
      $view .= '</p>';
    }
    $view .= '<p style="margin: 20px auto 30px auto; width: 90%;">';
    $view .= '<a href="edit.php?MR_ID=' . $MR_ID . '".php>';
    $view .= '編集する';
    $view .= '</a>';
    $view .= '</p>';
    echo $view;
  }
  ?>
</body>

</html>