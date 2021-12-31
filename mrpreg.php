<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<form action="share_act.php" method="POST" onsubmit="return confirm_test()">
  <input type="text" name="ROUTINE_NAME" placeholder="モーニングルーティン名を設定してください"/>
  <table>

  </table>
  <p>+</p>
  <input type="text" name="DESCRIPTION" placeholder="コメント（任意）"/>
  <input type="text" name="YOUTUBE" placeholder="YouTube動画のリンクを入れてください（任意）"/>
  <p>みんなにシェア<input type="checkbox" name="SHARED"></p>
  <input type="submit" value="登録" />
</form>
</body>
</html>