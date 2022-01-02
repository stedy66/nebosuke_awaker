<?php 
//0. SESSION開始！！
session_start();

include('funcs.php');

//2. DB接続します
$pdo = db_conn();

//LOGINチェック → funcs.phpへ関数化しましょう！
sschk();


$end_time = '';
$period = '';


//2．データ登録SQL作成
//prepare("")の中にはmysqlのSQLで入力したINSERT文を入れて修正すれば良いイメージ
$stmt = $pdo->prepare("SELECT* FROM table3_test");
$status = $stmt->execute();

//loop through the returned data
while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){

    $end_time = $end_time . '"'.date('H:i', strtotime($r["end_time"])).'",';
    $period = $period . '"'.date('H:i', strtotime($r["period"])) .'",';
    $date = date('m月d日', strtotime($r["date"]));

}

$end_time = trim($end_time,",");
$period = trim($period,",");
// echo $period;



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>私の記録</title>
</head>
<body>
<h2 style="text-align: center">ログ</h2>
<h1 style="text-align: center"><?=$date?></h1>
<div style="width:600px;margin: auto;">
<canvas id="myChart" width="600" height="300"></canvas>
</div>

<!-- Main[Start] -->
<section id = "main">
<div style="text-align: center">
    <img src="./img/kao1.png" alt="">
    <img src="./img/kao2.png" alt="">
    <img src="./img/kao3.png" alt="">
    <img src="./img/kao4.png" alt="">
    <img src="./img/kao5.png" alt="">
</div>
<form method="POST" action="logshare_act.php">
  <div class="jumbotron">
    <table border="1" cellpadding="5" cellspacing="0" width="600" style="margin: auto">
        <tr>
        <th>Action</th>     
        <th>Time</th> 
        <th>Plan</th>
        <th>Practice</th>
        </tr>
        <?php
        //2．データ登録SQL作成
        //prepare("")の中にはmysqlのSQLで入力したINSERT文を入れて修正すれば良いイメージ
        $stmt = $pdo->prepare("SELECT* FROM table3_test");
        $status = $stmt->execute();

        //３．データ表示
        if($status==false) {
            //SQLエラーの場合
            sql_error($stmt);
        }else{
            //SQL成功の場合
            while( $r = $stmt->fetch(PDO::FETCH_ASSOC)){ //データ取得数分繰り返す
                echo '<td>'.$r["action"].'</td>';
                echo '<td>'.$r["period"].'</td>'; 
                echo '<td>'.$r["date"].'</td>';
                echo '<td><time>'.date('H:i', strtotime($r["end_time"])).'</time></td></tr>';
            }
        }
        ?>
    </table>

    <div style="text-align: center"><input type="submit" value="記録をシェア"></div>
  </div>
</form>


</section>
<!-- Main[End] -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php echo $end_time ?>],//各棒の名前（name)
        // labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'ほげ'],//各棒の名前（name)
        datasets: [{
            label: '覚醒度',
            //data: [<?php echo $period ?>],//各縦棒の高さ(値段)
            data: [3, 10, 3, 5, 15, 20, 10, 30, 15, 100],//各縦棒の高さ(値段)
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                // 'rgba(54, 162, 235, 0.2)',
                // 'rgba(255, 206, 86, 0.2)',
                // 'rgba(75, 192, 192, 0.2)',
                // 'rgba(153, 102, 255, 0.2)',
                // 'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                // 'rgba(54, 162, 235, 1)',
                // 'rgba(255, 206, 86, 1)',
                // 'rgba(75, 192, 192, 1)',
                // 'rgba(153, 102, 255, 1)',
                // 'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
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