<?php
  session_start();
  include "database_connection.php";
  if(isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] == "POST"):
    $uid      = $_SESSION['user_id'];
    $username =  '%'. $_POST['username'] .'%';
    $stmt = $connect->prepare("SELECT * FROM `login` WHERE user_id != ? AND username LIKE ?");
    $stmt->execute(array($uid, $username));
    $results = $stmt->fetchAll();
    $count = $stmt->rowCount();
    if($count > 0):
      echo '<ul>';
      foreach($results as $result):
        echo '<li><a href="profile.php?id='. $result['user_id'] .'">'. $result['name'] .'</a></li>';
      endforeach;
      echo '</ul>';
    endif;
  else:
    header('Location: index.php');
    exit();
  endif;
