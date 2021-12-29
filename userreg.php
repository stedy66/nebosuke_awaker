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
<script src="js/func.js"></script>
<title>会員登録</title>
</head>
<body>

<header>
  <nav class="navbar navbar-default">会員登録</nav>
</header>

<script>
function confirm_test() {
    var select = confirm("本当に登録しますか？\n「OK」で登録\n「キャンセル」で登録中止");
    return select;
}
</script>

<form action="userreg_act.php" method="POST" onsubmit="return confirm_test()">
<table>
  <tr>
    <td>登録情報</td>
    <td>入力欄</td>
  </tr>
  <tr>
    <td>ユーザー名<sup>*</sup>:</td>
    <td>
      <input type="text" name="name" placeholder="好きな名前を設定してください" required value="<?=$_SESSION["USER_NAME"]?>"/>
    </td>
  </tr>
  <tr>
    <td>ID<sup>*</sup>:</td>
    <td>
      <input type="text" name="USER_ID" placeholder="4文字以上の英数字を入力してください" required value="<?=$_SESSION["lid"]?>" pattern="^([a-zA-Z0-9]{4,})$" title="4文字以上の英数字のみで入力して下さい"/>
      <?php
      if ($_SESSION["duplicate"]==1) {
        echo "そのIDは使用できません、別のID名を指定してください";
        $_SESSION["duplicate"]=0;
      }
      ?>
    </td>
  </tr>
  <tr>
    <td>Password<sup>*</sup>:</td>
    <td>
      <input type="password" id="input_pass" name="lpw" placeholder="8文字以上の英数字を入力してください" required pattern="^([a-zA-Z0-9]{8,})$" title="8文字以上の英数字のみで入力して下さい"/><button id="btn_passview">表示</button>
    </td>
  </tr>
  <tr>
    <td>Password（再入力）<sup>*</sup>:</td>
    <td>
      <input type="password" name="lpw2" id="input_pass2" placeholder="Passwordを再入力してください" required pattern="^([a-zA-Z0-9]{8,})$" title="8文字以上の英数字のみで入力して下さい"/><button id="btn_passview2">表示</button>
      <?php
      if ($_SESSION["mismatch"]==1) {
        echo "Passwordが一致しません";
        $_SESSION["mismatch"]=0;
      }
      ?>
    </td>
  </tr>
  <tr>
    <td>e-mail<sup>*</sup>:</td>
    <td>
      <input type="email" name="email" placeholder="example@test.ne.jp" value="<?=$_SESSION["email"]?>" required/>
    </td>
  </tr>
  <tr>
    <td>生年月日:</td>
    <td>
      <input type="date" name="birthday" value="<?=$_SESSION["birthday"]?>"/>
    </td>
  </tr>
  <tr>
    <td>性別:</td>
    <td>
      <select name="sex">
        <option value="">--選択してください--</option>
        <option value="male" <?php if ($_SESSION["sex"]=="male") {echo "selected";}?>>男性</option>
        <option value="female" <?php if ($_SESSION["sex"]=="female") {echo "selected";}?>>女性</option>
      </select>
    </td>
  </tr>
</table>
*は必須入力項目です。<br>
<input type="submit" value="登録" />
<button onclick="location.href='login.php'">戻る</button>
</form>

<script>
  //パスワードの表示非表示切り替え
  switching("input_pass","btn_passview");
  switching("input_pass2","btn_passview2");
</script>
</body>
</html>