<?php
session_start();

//$follow_idはユーザーのID、folllowed_idはルーティーンを登録したユーザーのID
$follow_id = $_SESSION['USER_ID'];
$followed_id = $_GET["USER_ID"];

include("funcs.php");
sschk();
$pdo = db_conn();

$user_stmt = $pdo->prepare("SELECT * FROM table4 WHERE USER_ID=:USER_ID");
$user_stmt->bindValue(":USER_ID", $followed_id, PDO::PARAM_STR);
$user_status = $user_stmt->execute();

if ($user_status == false) {
    sql_error($user_status);
} else {
    $user = $user_stmt->fetch(pdo::FETCH_ASSOC);
}


//フォロー数の取得
// follow_table からこのルーティーンを登録したユーザーがフォローしている数を取得
$stmt_follow = $pdo->prepare('SELECT COUNT(*) FROM follow_table WHERE follow_id=:follow_id;');
$stmt_follow->bindValue(':follow_id', $followed_id, PDO::PARAM_STR);
$status_follow = $stmt_follow->execute();
$result_follow = $stmt_follow->fetch(PDO::FETCH_ASSOC);


// follow_table からこのルーティーンを登録したユーザーがフォローされている人数を取得
$stmt_followed = $pdo->prepare('SELECT COUNT(*) FROM follow_table WHERE followed_id=:followed_id;');
$stmt_followed->bindValue(':followed_id', $followed_id, PDO::PARAM_STR);
$status_followed = $stmt_followed->execute();
$result_followed = $stmt_followed->fetch(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/user_detail.css">
    <link rel="stylesheet" href="css/top2style-copy.css">
    <title>Document</title>
</head>

<body>
    <header>
        <div class="home">ユーザーデータページ</div>
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

    <!-- 画像、ルーティン -->
    <div id="gazou">
        <img src="upload/default_bg.jpg" alt=""  >
    </div>
    <div class="profile">
        <div class="profile_icon">
        <img class="icon" src="upload/<?= $user['icon'] ?> " alt="">
        </div>
        <div class="profile_name"><?= $user["USER_NAME"] ?></div>
    </div>
    <?php
    // if (!isset($user['icon'])) {
    //     echo '<img style="width:50px;" src="img/kao2.png" alt="">';
    // } else {
    //     echo '<img style="width:50px;" src="upload/' . $user['icon'] . '" alt="">';
    // }
    ?> 
    <div id="follow">
        <div class="follow"><p><?= $result_follow["COUNT(*)"] ?> フォロー</p></div>
        <div class="followed"><p><?= $result_followed["COUNT(*)"] ?> フォロワー</p></div>
    </div>
    <?php if ($follow_id != $followed_id) : ?>
        <!-- ここから再開
        user_follow_delete.php
        user_follow.phpを作る
        user_follow.php及びuser_follow_delete.phpのURLの後ろがおかしいので修正する -->
        <?php if (check_follow($follow_id, $followed_id)) : ?>
                <form action="user_follow_delete.php?USER_ID=<?php echo $followed_id ?>" method="post">
                    <input type="hidden" name="follow_id" value="<?= $follow_id ?>">
                    <input type="hidden" name="followed_id" value="<?= $followed_id ?>">
                    <input type="submit" value="フォロー中" class="btn">
                </form>
        <?php else : ?>
                <form action="user_follow.php" method="post">
                    <input type="hidden" name="follow_id" value="<?= $follow_id ?>">
                    <input type="hidden" name="followed_id" value="<?= $followed_id ?>">
                    <input type="submit" value="フォロー" class="btn">
                </form>
        <?php endif; ?>
    <?php endif; ?>
    
</body>

</html>