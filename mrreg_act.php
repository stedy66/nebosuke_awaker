<?php
session_start();
echo $_POST["ROUTINE_NAME"];
echo $_POST["DESCRIPTION"];
echo $_POST["YOUTUBE"];
if (isset($_POST["SHARED"])) {
  echo 1;
} else {
  echo 0;
}
echo '<br>';
$i=1;
while (isset($_POST["STEP_ID".$i])) {
  echo $_POST["STEP_ID".$i].'<br>';
  echo $_POST["DESCRIPTION".$i].'<br>';
  echo $_POST["TIME".$i].'<br>';
  $i++;
}

include("funcs.php");
sschk();
$pdo = db_conn();
?>