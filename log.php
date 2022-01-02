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
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/log.css">
    <title>私の記録</title>
</head>
<body>
<!-- Main[Start] -->
<header>
<h1>ログ</h1>
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
        $stmt = $pdo->prepare("SELECT* FROM table3_test");
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
                //echo '<td>'.$r["date"].'</td>';
                echo '<td><time>'.date('H:i', strtotime($r["end_time"])).'</time></td></tr>';
            }
        }
        ?>
      </table>

      <div class="btn"><input type="submit" value="記録シェア"></div>
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
                'rgba(255, 201, 0, 0.2)',
                // 'rgba(54, 162, 235, 0.2)',
                // 'rgba(255, 206, 86, 0.2)',
                // 'rgba(75, 192, 192, 0.2)',
                // 'rgba(153, 102, 255, 0.2)',
                // 'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 201, 0, 1)',
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