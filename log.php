<?php 
//0. SESSION開始！！
session_start();

// session_start(['cookie_lifetime' => $10]);

include('funcs.php');

//2. DB接続します
$pdo = db_conn();

//LOGINチェック → funcs.phpへ関数化しましょう！
sschk();

$plan_time ='';
$end_time = '';
$step = '';

//日付選択関係
// unset($_SESSION['log_day']);
$day = '';
// $day = $_SESSION['log_day'];
// $day = "2022-01-01";
// unset($_SESSION['log_day']);
// echo $_SESSION['log_day'];
// echo $day;

echo $_SESSION['log_pday'];
echo $_SESSION['log_nday'];
echo $_SESSION['log_day'];
// var_dump($_SESSION['log_pday']);
// var_dump($_SESSION['log_nday']);
// var_dump($_SESSION['asd']);
//exit;
// if(isset($_SESSION["log_pday"])){
//     $day = $_SESSION['log_pday'];
// }else if(isset($_SESSION["log_nday"])){
//     $day = $_SESSION['log_nday'];
// }

// $day = $_SESSION['log_day'];
echo $day;



//表示する日付のデータ取得SQL作成
//prepare("")の中にはmysqlのSQLで入力したINSERT文を入れて修正すれば良いイメージ
//if(!isset($day) || $day == 0){
if(!isset($_SESSION['log_day'])){
    //初閲覧時に最新の日付のデータを取得する
    $stmt = $pdo->prepare("SELECT* FROM table3_test WHERE date = (select max(date)from table3_test) ORDER BY step");
}else{
    //前後の日付のデータを取得する
    $stmt = $pdo->prepare("SELECT* FROM table3_test WHERE date = '$day' ORDER BY step");
}
//SQLの実行分
$status = $stmt->execute();


//チャート用のデータを一行ごとに取得
while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){

    $plan_time .= '"' . $r["plan"] . '",';
    $end_time .= '"' .date('H:i', strtotime($r["end_time"])) .'",';
    //$period = $period . '"'.date('H:i', strtotime($r["period"])) .'",';
    $step = $step .'"' .$r["action"] .'",';
    $date = date('m月d日', strtotime($r["date"]));

}

$plan_time = trim($plan_time,",");
//var_dump($end_time);
$end_time = trim($end_time,",");
//$step = trim($step,",");
// $key = array_key_last($end_time);
//echo $end_time[$key];
//echo end($end_time);
//var_dump($end_time);

//前後日付を表示するための変数作成用のSQL作成
//prepare("")の中にはmysqlのSQLで入力したINSERT文を入れて修正すれば良いイメージ
$stmt2 = $pdo->prepare("SELECT date FROM table3_test GROUP BY date");



unset($_SESSION['log_day']);
unset($_SESSION['log_pday']);
unset($_SESSION['log_nday']);


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
    <script src="js/jquery-2.1.3.min.js"></script>
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

<section id = "main">

<!-- <form method="post" style = "margin: 20px">
    <?php
    // //SQLの実行分
    // $status2 = $stmt2->execute();
    // $option="";
    // //loop through the returned data
    // while( $r = $stmt2->fetch(PDO::FETCH_ASSOC)){
    //     //$dates .= date('m月d日', strtotime($r["date"]));
    //     $option .= '<option value='.$r["date"].'>'.date('m月d日', strtotime($r["date"])).'</option>';
    // }
    //     $view .= '<select name="days">';
    //     $view .= "<option value=0 selected>--選択してください--</option>";
    //     $view .= $option;
    //     $view .= '</select><br>';
    //     echo $view;
    // ?>
    <input type="submit" name="btn_submit" value="表示" style = "margin: 10px">
</form> -->

<div class = "days">
    <div><p id="pd"><<</p></div>
    <h2 style="text-align: center"><?=$date?></h2>
    <div><p id="nd">>></p></div>
</div>

<div class="chart">
<canvas id="myChart" width="600" height="300"></canvas>
</div>
<form class="form" method="POST" action="logshare_act.php">

    <input type="text" name="ROUTINE_NAME" class="comment" placeholder="コメント"/><br>

    <input type="radio" name="radio" value="1" id="radio1">
    <label for="radio1"></label>
    <input type="radio" name="radio" value="2" id="radio2">
    <label for="radio2"></label>
    <input type="radio" name="radio" value="3" id="radio3">
    <label for="radio3"></label>
    <input type="radio" name="radio" value="4" id="radio4">
    <label for="radio4"></label>
    <input type="radio" name="radio" value="5" id="radio5">
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
                    stepSize: 30,
                    displayFormats: {
                        'hour': 'HH:mm'
                    }
                },
                //X軸の範囲を指定
                ticks: {
                    min: '07:00',
                    max: '11:00'//'end($end_time)';
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

let d = "<?php echo $days ?>"
console.log(d);



//前日へ
$("#pd").on("click", function() {
    <?php $_SESSION['log_pday'] = '2022-01-01'; ?>
    alert('<?php echo $_SESSION["log_pday"]; ?>');
    window.location.reload();
    <?php //exit; ?>
});

//次日へ
$("#nd").on("click", function() {
    <?php $_SESSION['log_nday'] = '2022-01-02'; ?>
    alert('<?php echo $_SESSION["log_nday"]; ?>');
    window.location.reload();
    <?php //exit; ?>
});


</script>
</body>
</html>