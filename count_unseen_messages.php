<?php
  include "database_connection.php";
  session_start();

  if (isset($_SESSION['user_id'])):
    $to_user_id = $_SESSION['user_id'];
    $stmt = $connect->prepare('SELECT * FROM chat_message WHERE to_user_id = ? AND status = 1');
    $stmt->execute(array($to_user_id));
    $count = $stmt->rowCount();
    //$usr = $stmt->fetch();
    if($count > 0):
      echo $count;
    endif;
  else:
    header('Location:index.php');
    exit();
  endif;
?>
