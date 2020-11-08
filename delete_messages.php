<?php
  ob_start();
  session_start();
  include('database_connection.php');
  if(isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
    $from_user_id = $_SESSION['user_id'];
    $to_user_id   = $_POST['to_user_id'];
    $stmt = $connect->prepare('DELETE FROM chat_message WHERE from_user_id = ? AND to_user_id = ?');
    $stmt->execute(array($from_user_id, $to_user_id));
  } else {
    header('Location: index.php');
    exit();
  }
  ob_end_flush();
?>
