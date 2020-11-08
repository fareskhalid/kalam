<?php
  ob_start();
  session_start();
  include('database_connection.php');
  include('include/header.php');
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])):
    $friend_id = $_POST['add_friend_id'];
    $user = $_SESSION['user_id'];
    if ($friend_id == $user){
        echo '<div class="alert alert-danger text-center">Can\'t Send Friend Request to this user</div>';
        exit();
    }
    // Check if they are already friends
    $stmt = $connect->prepare('SELECT * FROM friends WHERE user_id = ? AND friend_id = ?');
    $stmt->execute(array($user, $friend_id));
    $info = $stmt->fetch();
    $count = $stmt->rowCount();
    if($count > 0){ // There are a friendship
        if ($info['block_state'] == 1){
          echo '<div class="alert alert-danger text-center">Blocked User</div>';
          $url = $_SERVER['HTTP_REFERER'];
          header("refresh:3;url=$url");
        } else {
          echo '<div class="alert alert-success text-center">Already Friends</div>';
          $url = $_SERVER['HTTP_REFERER'];
          header("refresh:3;url=$url");
        }
    } else{
        $stmt = $connect->prepare('INSERT INTO `friends` (`user_id`, `friend_id`, `block_state`) VALUES (?, ?, 0)');
        $stmt->execute(array($user, $friend_id));
        echo '<div class="alert alert-info text-center">Friend Request Sent!</div>';
        $url = $_SERVER['HTTP_REFERER'];
        header("refresh:3;url=$url");
    }
  else:
    header('location: index.php');
  endif;
  include('include/header.php');
  ob_end_flush();
?>
