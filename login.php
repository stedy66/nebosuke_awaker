<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">

<!-- <link rel="stylesheet" href="css/main.css" /> -->
<link rel="stylesheet" href="css/reset.css">
<link rel="stylesheet" href="css/login.css">

<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{padding: 10px;font-size:16px;}</style>
<title>ログイン</title>
</head>
<body>

  <header>
    <h1 class="header_colour"></h1>
  </header>
  <section id = "main">
  <div style="text-align: center">
    <img src="./img/nbsk_c.png" alt="" >
  </div>
  <div class="button_twitter">
    <img src="./img/twilog.png" alt="" >
    <p>Twitterアカウントとの連携について</p>
  </div>

  <!-- lLOGINogin_act.php は認証処理用のPHPです。 -->
  <?php
  if (isset($_SESSION["success"]) && $_SESSION["success"]==0) {
    echo "新しいアカウントが作成されました。ログイン情報を入力してログインしてください。";
    $_SESSION["success"]=-1;
  }
  elseif (isset($_SESSION["login"]) && $_SESSION["login"]==1) {
    echo "入力したアカウントが存在しません。アカウント情報を確認してください。";
    $_SESSION["login"]=0;
  }
  elseif (isset($_SESSION["login"]) && $_SESSION["login"]==2) {
    echo "パスワードが正しくありません。アカウント情報を確認してください。";
    $_SESSION["login"]=0;
  }
  ?>
  <form action="login_act.php" method="post">

    <div class="box">
      <label class="nid">nebosuke ID</label><br>
      <input type="text" name="USER_ID" class="Column">
    </div>
    <div class="box">
      <label class="nid">パスワード　</label><br>
      <input type="password" name="PASSWORD" class="Column">
    </div>

 
    <input type="submit" value="LOGIN" class="button1">
  </form>
  <div style="text-align: center">
    <a href="userreg.php" class="button2">新規登録</a>
  </div>

  </section>
  <footer>
    <h1 class="footer_colour"></h1>
  </footer>

</body>
</html>