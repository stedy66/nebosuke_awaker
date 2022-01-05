<?php 
//0. SESSION開始！！
session_start();

include('funcs.php');

//2. DB接続します
$pdo = db_conn();

//LOGINチェック → funcs.phpへ関数化しましょう！
sschk();

$plan_time ='';
$end_time = '';
$step = '';

//2．データ登録SQL作成
//prepare("")の中にはmysqlのSQLで入力したINSERT文を入れて修正すれば良いイメージ
$stmt = $pdo->prepare("SELECT* FROM table5");
$status = $stmt->execute();

//loop through the returned data
while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){
    
    $plan_time .= '"' . $r["plan"] . '",';
    $end_time .= '"' .date('H:i', strtotime($r["end_time"])) .'",';
    //$period = $period . '"'.date('H:i', strtotime($r["period"])) .'",';
    $step = $step .'"' .$r["action"] .'",';

}

$plan_time = trim($plan_time,",");
//var_dump($end_time);
$end_time = trim($end_time,",");
//$step = trim($step,",");
//$key = array_key_last($end_time);
//echo $end_time[$key];
//echo end($end_time);
//var_dump($end_time);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/log.css">
    <title>私の記録</title>
</head>
<body>
<!-- Main[Start] -->
<header class="triangle01">
<p><?php echo date('y年m月d日', strtotime($_GET["DATE"]));?>のモーニングルーティンログ</p>
</header>
<section id = "main">
<h2 style="text-align: center"><?=$date?></h2>
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
        //2．データ登録SQL作成
        //prepare("")の中にはmysqlのSQLで入力したINSERT文を入れて修正すれば良いイメージ
        $stmt = $pdo->prepare("SELECT* FROM table3_test ORDER BY step");
        $status = $stmt->execute();

        $i = 1;
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
</script>
</body>
</html>