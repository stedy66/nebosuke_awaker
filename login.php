<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="css/main.css" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{padding: 10px;font-size:16px;}</style>
<title>ログイン</title>
</head>
<body>

  <header>
    <nav class="navbar navbar-default">LOGIN</nav>
  </header>

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
  ID:<input type="text" name="USER_ID" />
  PW:<input type="password" name="PASSWORD" />
  <input type="submit" value="LOGIN" />
  </form>

  <a href="user_reg.php">新規登録</a>

</body>
</html>