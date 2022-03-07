<?php
session_start();
//GET送信されたMR_IDを取得
$USER_ID = $_GET["USER_ID"];
include("funcs.php");
sschk();
$pdo = db_conn();

$user_stmt = $pdo->prepare("SELECT * FROM table4 WHERE USER_ID=:USER_ID");
$user_stmt->bindValue(":USER_ID", $USER_ID, PDO::PARAM_INT);
$user_status = $user_stmt->execute();

if ($user_status == false) {
    sql_error($user_status);
} else {
    $user = $user_stmt->fetch(pdo::FETCH_ASSOC);
}

//フォロー数の取得
// follow_table からこのルーティーンを登録したユーザーがフォローしている数を取得
$stmt_follow = $pdo->prepare('SELECT COUNT(*) FROM follow_table WHERE follow_id=:follow_id;');
$stmt_follow->bindValue(':follow_id', $USER_ID, PDO::PARAM_STR);
$status_follow = $stmt_follow->execute();
$result_follow = $stmt_follow->fetch(PDO::FETCH_ASSOC);


// follow_table からこのルーティーンを登録したユーザーがフォローされている人数を取得
$stmt_followed = $pdo->prepare('SELECT COUNT(*) FROM follow_table WHERE followed_id=:followed_id;');
$stmt_followed->bindValue(':followed_id', $USER_ID, PDO::PARAM_STR);
$status_followed = $stmt_followed->execute();
$result_followed = $stmt_followed->fetch(PDO::FETCH_ASSOC);


$follow_id = $_SESSION['USER_ID'];
$followed_id = $_GET["USER_ID"];

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
    if (!isset($user['icon'])) {
        echo '<img style="width:50px;" src="img/kao2.png" alt="">';
    } else {
        echo '<img style="width:50px;" src="upload/' . $user['icon'] . '" alt="">';
    }
    ?>
    <!-- 画像、ルーティン -->
    <p><?= $result_follow["COUNT(*)"] ?> フォロー</p>
    <p><?= $result_followed["COUNT(*)"] ?> フォロワー</p>
    <?php if ($follow_id != $USER_ID) : ?>

        <!-- ここから再開
        user_follow_delete.php
        user_follow.phpを作る
        user_follow.php及びuser_follow_delete.phpのURLの後ろがおかしいので修正する -->
        <?php if (check_follow($follow_id, $followed_id)) : ?>
            <form action="user_follow_delete.php?MR_ID=<?php echo $MR_ID ?>" method="post">
                <input type="hidden" name="current_user_id" value="<?= $current_user ?>">
                <input type="hidden" name="follow_user_id" value="<?= $profile_user ?>">
                <input type="submit" value="フォロー中">
            </form>
        <?php else : ?>
            <form action="user_follow.php?MR_ID=<?php echo $MR_ID ?>" method="post">
                <input type="hidden" name="current_user_id" value="<?= $current_user ?>">
                <input type="hidden" name="follow_user_id" value="<?= $profile_user ?>">
                <input type="submit" value="フォロー">
            </form>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>