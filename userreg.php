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
  <link rel="stylesheet" href="css/userreg.css">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    div {
      padding: 10px;
      font-size: 16px;
    }
  </style>
  <script src="js/func.js"></script>
  <script src="js/jquery-3.6.0.min.js"></script>
  <title>会員登録</title>


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
      <h1 class="a">新規登録</h1>
    </div>
    <script>
      function confirm_test() {
        var select = confirm("本当に登録しますか？\n「OK」で登録\n「キャンセル」で登録中止");
        return select;
      }
    </script>


    <form action="userreg_act.php" method="POST" enctype="multipart/form-data" onsubmit="return confirm_test()">
      <table>
        <tr>
          <th>登録情報</th>
          <th>入力欄　　　</th>
        </tr>
        <tr>
          <td>　ID<sup>*</sup>:</td>
          <td>
            <input type="text" name="USER_ID" placeholder="4文字以上の英数字を入力してください" required value="<?php if (isset($_SESSION["USER_ID"])) {
                                                                                                  echo $_SESSION["USER_ID"];
                                                                                                } else {
                                                                                                  echo '';
                                                                                                } ?>" pattern="^([a-zA-Z0-9]{4,})$" title="4文字以上の英数字のみで入力して下さい" class="Column" />
            <?php
            if (!isset($_SESSION["duplicate"])) {
              echo '';
            } else if ($_SESSION["duplicate"] == 1) {
              echo "そのIDは使用できません、別のIDを指定してください";
              $_SESSION["duplicate"] = 0;
            }
            ?>
          </td>
        </tr>
        <tr>
          <td>　ユーザー名<sup>*</sup>:</td>
          <td>
            <input type="text" name="USER_NAME" placeholder="好きな名前を設定してください" required value="<?php if (isset($_SESSION["USER_NAME"])) {
                                                                                                echo $_SESSION["USER_NAME"];
                                                                                              } else {
                                                                                                echo '';
                                                                                              } ?>" class="Column" />
          </td>
        </tr>
        <tr>
          <td>　Password<sup>*</sup>:</td>
          <td>
            <input type="password" id="input_pass1" name="PASSWORD" placeholder="8文字以上の英数字を入力してください" required pattern="^([a-zA-Z0-9]{8,})$" title="8文字以上の英数字のみで入力して下さい" class="Column" />
          </td>
        </tr>
        <tr>
          <td></td>
          <td><label>パスワードを表示する<input type="checkbox" id="password-check1" /></label></td>
        </tr>
        <tr>
          <td>　Password(再入力)<sup>*</sup>:　</td>
          <td>
            <input type="password" name="PASSWORD2" id="input_pass2" placeholder="Passwordを再入力してください" required pattern="^([a-zA-Z0-9]{8,})$" title="8文字以上の英数字のみで入力して下さい" class="Column">
            <?php
            if (!isset($_SESSION["mismatch"])) {
            } else if ($_SESSION["mismatch"] == 1) {
              echo "Passwordが一致しません";
              $_SESSION["mismatch"] = 0;
            }
            ?>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><label>パスワードを表示する<input type="checkbox" id="password-check2" /></label></td>
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
            <input type="email" name="EMAIL" placeholder="example@test.ne.jp" class="Column" value="<?php if (isset($_SESSION["EMAIL"])) {
                                                                                                      echo $_SESSION["EMAIL"];
                                                                                                    } else {
                                                                                                      echo '';
                                                                                                    } ?>" required />
          </td>
        </tr>
        <tr>
          <td>　性別:</td>
          <td>
            <select name="GENDER" class="Column">
              <option value="" <?php if (!isset($_SESSION["GENDER"])) {
                                  echo "selected";
                                } ?>>--選択してください--</option>
              <option value=1 <?php if ($_SESSION["GENDER"] == 1) {
                                echo "selected";
                              } ?>>男性</option>
              <option value=2 <?php if ($_SESSION["GENDER"] == 2) {
                                echo "selected";
                              } ?>>女性</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>　生年月日:</td>
          <td>
            <input type="date" name="BIRTHDAY" class="Column" value="<?= $_SESSION["BIRTHDAY"] ?>" />
          </td>
        </tr>
        <tr>
          <td>　住所:</td>
          <td>
            <input type="text" name="ADDRESS" class="Column" value="<?php if (isset($_SESSION["ADDRESS"])) {
                                                                      echo $_SESSION["ADDRESS"];
                                                                    } else {
                                                                      echo '';
                                                                    } ?>" />
          </td>
        </tr>
        <tr>
          <td>　Twitter:</td>
          <td>
            <input type="text" name="TWITTER" class="Column" value="<?php if (isset($_SESSION["TWITTER"])) {
                                                                      echo $_SESSION["TWITTER"];
                                                                    } else {
                                                                      echo '';
                                                                    } ?>" />
          </td>
        </tr>
      </table>
      *は必須入力項目です。<br>
      <input type="submit" value="登録" class="button1" />
      <div style="text-align: center">
        <button onclick="location.href='login.php'" class="button2">戻る</button>
      </div>
    </form>
  </section>
  <footer>
    <h1 class="footer_colour"></h1>
  </footer>
  <script type="text/javascript">
    $(function() {
      //チェックボックスの変化時関数
      $("#password-check1").change(function() {
        if ($(this).prop("checked")) {
          //チェックONの場合
          $("#input_pass1").attr("type", "text");
        } else {
          //チェックOFFの場合
          $("#input_pass1").attr("type", "password");
        }
      });
    });
    $(function() {
      //チェックボックスの変化時関数
      $("#password-check2").change(function() {
        if ($(this).prop("checked")) {
          //チェックONの場合
          $("#input_pass2").attr("type", "text");
        } else {
          //チェックOFFの場合
          $("#input_pass2").attr("type", "password");
        }
      });
    });
  </script>
</body>

</html>