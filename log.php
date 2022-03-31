<?php 
//0. SESSION開始！！
session_start();

include('funcs.php');

//2. DB接続します
$pdo = db_conn();

//LOGINチェック → funcs.phpへ関数化しましょう！
sschk();

//初期化
$plan_time ='';
$end_time = '';
$step = '';
$days = '';
//エラーecho用
$test1 ='1';
$test2 ="2";

//日付選択関係
$days = $_POST[days];
// echo $days;

//2．グラフ作成用のデータ取得SQL作成
if(!isset($days)||$days == 0){
    $stmt = $pdo->prepare("SELECT * FROM table2 INNER JOIN table3_test ON table2.MR_ID=table3_test.MR_ID 
                            WHERE table2.USER_ID=:USER_ID and date= (select max(date)from table3_test AS us WHERE table3_test.USER_ID= us.user_id) ORDER BY step");
    $stmt->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_STR);
    // echo $test1;
}else{
    $stmt = $pdo->prepare("SELECT * FROM table2 INNER JOIN table3_test ON table2.MR_ID=table3_test.MR_ID 
                            WHERE table2.USER_ID=:USER_ID and date = '$days' ORDER BY step");
    $stmt->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_STR);
    // echo $test2;
}
//SQLの実行分
$status = $stmt->execute();

if ($status == false) {
    sql_error($stmt);
} else {
    //loop through the returned data
    while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){
        // $plan_time .= '"' . $r["plan"] . '",';
        $plan_time1 .= '"' .date('H:i', strtotime($r["plan"])) .'",';
        $end_time1  .= '"' .date('H:i', strtotime($r["end_time"])) .'",';
        //$period = $period . '"'.date('H:i', strtotime($r["period"])) .'",';
        $step = $step .'"' .$r["action"] .'",';
        $date = date('m月d日', strtotime($r["date"]));
        $datep = $r["date"];
    }
}
//折れ線グラフ表示関係
$plan_time = trim($plan_time1,",");
$end_time = trim($end_time1,",");

//グラフ上限下限の値作成
//planの最初の時間を取り出し開始時間にする
$start_time = array_values(preg_split("/[\s,]+/", $plan_time))[0];
//plan,endそれぞれ最後の時間を取り出す
$finish_time1 = end(preg_split("/[\s,]+/", $plan_time));
$finish_time2 = end(preg_split("/[\s,]+/", $end_time));
//planかendで最後の時間が遅い方を終了時間にする
if($finish_time1 > $finish_time2){
    $finish_time = $finish_time1;
}else{
    $finish_time = $finish_time2;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/log.css">
    <link rel="stylesheet" href="css/top2style-copy.css">
    <title>私の記録</title>
</head>
<body>
<!-- Main[Start] -->
<header>
        <div class="home">ログ</div>
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

<section id = "main">

<form method="post" style = "margin: 20px;text-align: center;">
    <?php
    //2．ログ選択用のデータ取得SQL作成
    //prepare("")の中にはmysqlのSQLで入力したINSERT文を入れて修正すれば良いイメージ
    // $stmt2 = $pdo->prepare("SELECT date FROM table3_test GROUP BY date");
    $stmt2 = $pdo->prepare("SELECT date FROM table2 INNER JOIN table3_test ON table2.MR_ID=table3_test.MR_ID WHERE table2.USER_ID=:USER_ID GROUP BY date");
    $stmt2->bindValue(":USER_ID", $_SESSION["USER_ID"], PDO::PARAM_STR);
    //SQLの実行分
    $status2 = $stmt2->execute();

    if ($status2 == false) {
        sql_error($stmt2);
    } else {
        $option="";
        //loop through the returned data
        while( $r2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
            $option .= '<option value='.$r2["date"].'>'.date('m月d日', strtotime($r2["date"])).'</option>';
        }
        $view .= '<select name="days">';
        $view .= "<option value=0 selected>--選択してください--</option>";
        $view .= $option;
        $view .= '</select>';
        echo $view;
    }
    ?>
    <input type="submit" name="btn_submit" value="表示" style = "margin: 10px">
</form>

<div class = "days">
    <!-- <div><p id="pd"><<</p></div> -->
    <h2 style="text-align: center"><?=$date?></h2>
    <!-- <div><p id="nd">>></p></div> -->
</div>

<div class="chart">
<canvas id="myChart" width="600" height="300"></canvas>
</div>
<form class="form" method="POST" action="logshare_act2.php">

    <input type="hidden" name="date" value=<?=$datep?>>   

    <input type="text" name="comment" class="comment" placeholder="コメント"/><br>

    <input type="radio" name="evaluation" value="Excellent" id="radio1">
    <label for="radio1"></label>
    <input type="radio" name="evaluation" value="VeryGood" id="radio2">
    <label for="radio2"></label>
    <input type="radio" name="evaluation" value="Good" id="radio3">
    <label for="radio3"></label>
    <input type="radio" name="evaluation" value="Average" id="radio4">
    <label for="radio4"></label>
    <input type="radio" name="evaluation" value="Poor" id="radio5">
    <label for="radio5"></label>

    <div class="jumbotron">
      <table border="1" cellpadding="5" cellspacing="0" >
        <tr>
        <th>Action</th>     
        <th>Time</th> 
        <th>Plan</th>
        <th>Practice</th>
        </tr>
        <?php
        //表の表示（グラフと同じ条件）
        //SQLの実行分
        $status = $stmt->execute();
        //３．データ表示
        if($status==false) {
            //SQLエラーの場合
            sql_error($stmt);
        }else{
            //SQL成功の場合
            while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ //データ取得数分繰り返す
                echo '<tr><td><name="action" value="5">'.$r["action"].'</td>';
                echo '<td>'.$r["time"].'min</td>'; 
                echo '<td><time>'.date('H:i', strtotime($r["plan"])).'</time></td>'; 
                //echo '<td>'.$r["date"].'</td>';
                echo '<td><time>'.date('H:i', strtotime($r["end_time"])).'</time></td></tr>';
            }
        }
        ?>
      </table>
        <div class="btn">
            <input type="submit" class="button" value="記録シェア">
        </div>

    </div>
</form>

</section>
<!-- Main[End] -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    //グラフタイプ
    type: 'line',
    //データ
    data: {
        //X軸データ
        labels: [<?php echo $step ?>],//各棒の名前（name)
        //labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'ほげ'],//各棒の名前（name)
        //labels: ['09:30', '11:10', '13:00', '15:00', '18:30', '19:50'],
        //データセット
        datasets: [{
            label: '覚醒度（実績）',
            //Y軸データ
            data: [<?php echo $end_time ?>],//各縦棒の高さ(値段)
            //data: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],//各縦棒の高さ(値段)
            //線の色
            borderColor: 'rgba(255, 201, 0, 1)',
            borderWidth: 1,
            //塗りつぶしの色
            fill: false
        }, {
            label: '覚醒度（プラン）',
            //Y軸データ
            data: [<?php echo $plan_time ?>],//各縦棒の高さ(値段)
            //data: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],//各縦棒の高さ(値段)
            //線の色
            borderColor: 'rgba(255, 0, 0, 1)',
            borderWidth: 1,
            //塗りつぶしの色
            fill: false
        }]
    },
    //グラフ設定
    options: {
        //凡例は非表示
        //legend: {
        //    display: false
        //},
        scales: {
            //X軸
            yAxes: [{
                //軸ラベル表示
                scaleLabel: {
                    display: true,
                //    labelString: 'TIME'
                },
                //ここで軸を時間を設定する
                type: 'time',
                time: {
                    parser: 'HH:mm',
                    unit: 'minute',
                    stepSize: 20,
                    displayFormats: {
                        'hour': 'HH:mm'
                    }
                },
                //X軸の範囲を指定
                ticks: {
                    //開始時間
                    // min: '07:00:00',
                    min:<?php echo $start_time ?>,
                    //終了時間
                    // max: '11:00:00'
                    max:<?php echo $finish_time ?>
                }
            }],
            xAxes: [{
                //軸ラベル表示
                scaleLabel: {
                    display: true,
                },
                //X軸の範囲を指定
                ticks: {
                    beginAtZero: true
                }
            }] 
        }
    }
});

// let d = "<?php echo $days ?>"
// console.log(d);
// //前日へ
// $("#pd").on("click", function() {
//     // $date->modify('-1 day');
// });
// //次の日へ
// $("#nd").on("click", function() {
//     // $date->modify('+1 day');
// });

</script>
</body>
</html>