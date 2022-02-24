<?php

include("funcs.php");
$pdo = db_conn();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>非ログイン者向け</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/top2style.css">
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/slick-theme.css">
</head>

<body>

    <header>
        <img src="img/ネボスケロゴ_文字黒.jpg" class="logo">
        <div class="home">
            Welcome
        </div>
        <!-- <div class="nav_item"><a href="#">ログアウト</a></div> -->
        <div class="hamburger-menu">
            <input type="checkbox" id="menu-btn-check">
            <label for="menu-btn-check" class="menu-btn"><span></span></label>
            <!--ここからメニュー-->
            <div class="menu-content">
                <ul>
                    <li>
                        <a href="userreg.php">新規登録</a>
                    </li>
                    <li>
                        <a href="login.php">ログイン</a>
                    </li>
                </ul>
            </div>
            <!--ここまでメニュー-->
        </div>
    </header>
    <p style="margin: 20px auto 30px auto; width: 600px; font-weight: 700; font-size:larger;">みんなのモーニングルーティン　<a style="text-decoration: none;" href="sharedmrp_view.php">すべて表示</a></p>
    <?php
    //みんなのMRの一覧取得
    $stmt = $pdo->prepare("SELECT * FROM table1_1 WHERE SHARED=1 ORDER BY DOWNLOAD_NUM DESC");
    $status = $stmt->execute();
    if ($status == false) {
        sql_error($stmt);
    } else {
        $i = 0;
        echo '<div class="multiple-items">';
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($i >= 10) {
                break;
            }
            $view = '';
            $view .= '<a style="text-decoration: none;" href="mrdetail_view.php?MR_ID=' . $r["MR_ID"] . '">';
            $view .= '<div>';
            if ($i % 3 == 0) {
                $view .= '<div class="mrp" style="background-image: url(img/pexels-engin-akyurt-2299028.jpg)">';
            } else if ($i % 3 == 1) {
                $view .= '<div class="mrp" style="background-image: url(img/pexels-tamba-budiarsana-979247.jpg)">';
            } else {
                $view .= '<div class="mrp" style="background-image: url(img/pexels-thought-catalog-904616.jpg)">';
            }
            // $view.='<img src="img/ネボスケロゴ_文字黒.jpg" class="logo">';
            $view .= '</div>';
            // $view.='<a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">';
            $view .= '<p class="mrp_name">' . $r["ROUTINE_NAME"] . '</p>';
            $view .= '</div>';
            $view .= '</a>';
            echo $view;

            // echo '<p style="margin: 20px auto 30px auto; width: 600px;"><a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">'.$r["ROUTINE_NAME"].'</a><p>';
            $i += 1;
        }
        echo '</div>';
    }
    ?>
    <p style="margin: 20px auto 30px auto; width: 600px; font-weight: 700; font-size:larger;">みんなの記録　<a style="text-decoration: none;" href="minlog_view.php">すべて表示</a></p>
    <?php
    //みんなのMRの一覧取得
    $stmt = $pdo->prepare("SELECT * FROM table1_1 WHERE SHARED=1 ORDER BY DOWNLOAD_NUM DESC");
    $status = $stmt->execute();
    if ($status == false) {
        sql_error($stmt);
    } else {
        $i = 0;
        echo '<div class="multiple-items">';
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($i >= 10) {
                break;
            }
            $view = '';
            $view .= '<a style="text-decoration: none;" href="logshere2_view.php?MR_ID=' . $r["MR_ID"] . ' & USER_ID=' . $r["USER_ID"] . '">';
            $view .= '<div>';
            if ($i % 3 == 0) {
                $view .= '<div class="mrp" style="background-image: url(img/pexels-engin-akyurt-2299028.jpg)">';
            } else if ($i % 3 == 1) {
                $view .= '<div class="mrp" style="background-image: url(img/pexels-tamba-budiarsana-979247.jpg)">';
            } else {
                $view .= '<div class="mrp" style="background-image: url(img/pexels-thought-catalog-904616.jpg)">';
            }
            // $view.='<img src="img/ネボスケロゴ_文字黒.jpg" class="logo">';
            $view .= '</div>';
            // $view.='<a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">';
            $view .= '<p class="mrp_name">' . $r["ROUTINE_NAME"] . '</p>';
            $view .= '</div>';
            $view .= '</a>';
            echo $view;

            // echo '<p style="margin: 20px auto 30px auto; width: 600px;"><a href="mrdetail.php?MR_ID='.$r["MR_ID"].'">'.$r["ROUTINE_NAME"].'</a><p>';
            $i += 1;
        }
        echo '</div>';
    }
    ?>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>