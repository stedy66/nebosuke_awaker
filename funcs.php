<?php
//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str){
    return htmlspecialchars($str, ENT_QUOTES);
}

//DB接続
//さくらサーバー接続
// function db_conn(){
//   try {
//     $db_name = "nebosuke_awaker";    //データベース名
//     $db_id   = "nebosuke";      //アカウント名
//     $db_pw   = "shuy7424";      //パスワード：XAMPPはパスワード無しに修正してください。
//     $db_host = "mysql57.nebosuke.sakura.ne.jp"; //DBホスト
//     return new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);
//   } catch (PDOException $e) {
//     exit('DB Connection Error:'.$e->getMessage());
//   }
// }

function db_conn(){
  try {
    $db_name = "nebosuke_test";    //データベース名
    $db_id   = "root";      //アカウント名
    $db_pw   = "root";      //パスワード：XAMPPはパスワード無しに修正してください。
    $db_host = "localhost"; //DBホスト
    return new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);
  } catch (PDOException $e) {
    exit('DB Connection Error:'.$e->getMessage());
  }
}

//SQLエラー
function sql_error($stmt){
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit("SQLError:".$error[2]);
}

//リダイレクト
function redirect($file_name){
    header("Location: ".$file_name);
    exit();
}

//SessionCheck
function sschk(){
  if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!=session_id()){
    //ログインしていない状態でアクセスされたらログイン画面に遷移させる
    //ログイン先でアラートが出るようにセッションに変数を代入
    //リダイレクト先で0を代入する
    $_SESSION["errorLog"] = 1;
    header("Location:login.php");
  }else{
    session_regenerate_id(true);
    $_SESSION["chk_ssid"] = session_id();
  }
}

//fileUpload("送信名","アップロード先フォルダ");
//ファイルアップロードに関する関数
function fileUpload($fname, $path)
{
  if (isset($_FILES[$fname]) && $_FILES[$fname]["error"] == 0) {
    //ファイル名取得
    $file_name = $_FILES[$fname]["name"];
    //一時保存場所取得
    $tmp_path  = $_FILES[$fname]["tmp_name"];
    //拡張子取得
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);
    //ユニークファイル名作成
    $file_name = date("YmdHis") . md5(session_id()) . "." . $extension;
    // FileUpload [--Start--]
    $file_dir_path = $path . $file_name;
    if (is_uploaded_file($tmp_path)) {
      if (move_uploaded_file($tmp_path, $file_dir_path)) {
        chmod($file_dir_path, 0644);
        return $file_name; //成功時：ファイル名を返す
      } else {
        return 1; //失敗時：ファイル移動に失敗
      }
    }
  } else {
    return 2; //失敗時：ファイル取得エラー
  }
}

//デバック用
function dd($arr) {

  echo "<pre>";
  var_dump($arr);
  echo "</pre>";
  exit;
}

function check_follow($follow_user, $follower_user)
{
  $pdo = db_conn();
  $sql = "SELECT follow_id,followed_id
          FROM follow_table
          WHERE :followed_id = followed_id AND :follow_id = follow_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':follow_id' => $follow_user,
    ':followed_id' => $follower_user
  ));
  return  $stmt->fetch();
}

function check_like($like_user, $like_MR)
{
  $pdo = db_conn();
  $sql = "SELECT USER_ID, MR_ID
          FROM like_table
          WHERE :MR_ID = MR_ID AND :USER_ID = USER_ID";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':USER_ID' => $like_user,
    ':MR_ID' => $like_MR
  ));
  return  $stmt->fetch();
}
