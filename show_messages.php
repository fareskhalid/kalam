<?php
  include "database_connection.php";

  session_start();

  if ($_SERVER['REQUEST_METHOD'] = 'POST' && isset($_SESSION['user_id'])) {

    // Connect to database
    $stmt = $connect->prepare("SELECT * FROM chat_message WHERE to_user_id = ? ORDER BY timestamp DESC LIMIT 10");
    $stmt->execute(array($_SESSION['user_id']));
    $msgs = $stmt->fetchAll();
    if ($stmt->rowCount() > 0){
      foreach ($msgs as $msg) {
        $stmt2 = $connect->prepare('SELECT * FROM login WHERE user_id = ?');
        $stmt2->execute(array($msg['from_user_id']));
        $usrs = $stmt2->fetch();
        if ($msg['status'] == 0){
          echo '<li class="un-seen"><a href="profile.php?id='. $usrs['user_id'] .'" class="text-primary">'. $usrs['username'] .'</a> : '. $msg['chat_message'] .'</li>';
        } else {
          echo '<li><a href="profile.php?id='. $usrs['user_id'] .'" class="text-primary">'. $usrs['username'] .'</a> : '. $msg['chat_message'] .'</li>';
        }
      }
    } else {
      echo '<div class="text-danger">No Messeges</div>';
    }

  } else {

    header('Location: index.php');

    exit();

  }
?>
