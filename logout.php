<?php

//ログアウトのための記述

session_start();

//セッションに保存された配列に空の配列を上書き
$_SESSION = array();

//session_name()は現在のセッションの配列名(PHPSESSID)を取得する
//setcookie()はセッションにデータを保存する(基本使わない)
//第一引数にはCookie内のキー名
//第二引数にはセットする値を入れる、ここでは''を代入して空にする
//第三引数には、有効期限(unix方式)
//第四引数には、cookieを有効にするpathを記述(全体の場合/を記述)

if(isset($_COOKIE[session_name()])){
    setcookie(session_name(),'', time()-42000, '/');
}

//セッション情報の削除
session_destroy();
header('Location:login.php');
exit();
