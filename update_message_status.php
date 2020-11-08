<?php
  include "database_connection.php";

  session_start();

  if ($_SERVER['REQUEST_METHOD'] = 'POST' && isset($_SESSION['user_id'])):
    $to_userId = $_POST['to_user_id'];
    $from_userId = $_SESSION['user_id'];
    $stmt = $connect->prepare('UPDATE `chat_message` SET `status`= 0 WHERE to_user_id = ? AND from_user_id = ?');
    $stmt->execute(array($from_userId, $to_userId));
  endif;
?>
