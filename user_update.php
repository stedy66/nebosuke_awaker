<?php
session_start();
include("funcs.php");
sschk();
$pdo = db_conn();

$id = $_SESSION['USER_ID'];



$stmt = $pdo->prepare("SELECT * FROM table4 WHERE USER_ID=:USER_ID");
$stmt->bindValue(':USER_ID', $id, PDO::PARAM_STR);
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if ($status == false) {
  sql_error($stmt);
}

//4. 抽出データ数を取得
$user = $stmt->fetch();



?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <!-- <link rel="stylesheet" href="css/main.css" /> -->
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/userreg.css">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
  <script src="js/func.js"></script>
  <title>ユーザ情報更新</title>
</head>

<body>
  <header>
    <h1 class="header_colour"></h1>
  </header>
  <section id="main">
    <div style="text-align: center">
      <img src="./img/nbsk_c.png" alt="">
    </div>
    <div style="text-align: center">
      <h1 class="a">ユーザ情報更新</h1>
    </div>
    <script>
      function confirm_test() {
        var select = confirm("本当に登録しますか？\n「OK」で登録\n「キャンセル」で登録中止");
        return select;
      }
    </script>

    <form action="user_update_act.php" method="POST" enctype="multipart/form-data" onsubmit="return confirm_test()">
      <table>
        <tr>
          <th>登録情報</th>
          <th>入力欄　　　</th>
        </tr>
        <tr>
          <td>　アイコン画像:</td>
          <td>
            <input id="upfile" type="file" accept="image/*" capture="camera" name="upfile">
          </td>
        </tr>
        <tr>
          <td>　e-mail<sup>*</sup>:</td>
          <td>
            <input type="email" name="EMAIL" placeholder="example@test.ne.jp" class="Column" value="<?= $user['EMAIL'] ?>" required />
          </td>
        </tr>
        <tr>
          <td>　住所:</td>
          <td>
            <input type="text" name="ADDRESS" class="Column" value="<?php if ($user['ADDRESS'] != '') {
                                                                      echo $user['ADDRESS'];
                                                                    } else if ($user['ADDRESS'] == '') {
                                                                      echo '';
                                                                    } ?>" />
          </td>
        </tr>
        <tr>
          <td>　Twitter:</td>
          <td>
            <input type="text" name="TWITTER" class="Column" value="<?php if ($user['TWITTER'] != '') {
                                                                      echo $user['TWITTER'];
                                                                    } else if ($user['TWITTER'] == '') {
                                                                      echo '';
                                                                    } ?>" />
          </td>
        </tr>
      </table>
      <input type="submit" value="更新" class="button1" />
    </form>
  </section>
  <footer>
    <h1 class="footer_colour"></h1>
  </footer>
</body>

</html>