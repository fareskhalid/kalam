<?php

//fetch_user.php

include('database_connection.php');

session_start();
if (isset($_SESSION['user_id'])){

  // New Selection Of friends And not blocked
  $query = "SELECT login.* , friends.user_id As fruser_id, friends.friend_id, friends.block_state FROM login, friends WHERE friends.user_id = ". $_SESSION['user_id'] ." AND friends.friend_id = login.user_id AND friends.block_state = 0;";
  $statement = $connect->prepare($query);

  $statement->execute();

  $result = $statement->fetchAll();

  $output = '
  <table class="table table-bordered table-striped">
  <tr>
    <th width="80%">Name</td>
    <th width="20%">Action</td>
  </tr>
  ';

  foreach($result as $row){
    $status = '';
    $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
    $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
    $user_last_activity = fetch_user_last_activity($row['user_id'], $connect);
    if($user_last_activity > $current_timestamp){
      $status = '<span class="label label-success status-span"></span>';
    }else{
      $status = '<span class="label label-danger status-span"></span>';
    }
    $output .= '
    <tr>
      <td>'.$status.'<a href="profile.php?id='. $row['user_id'] .'">'.$row['name'].'</a> '.count_unseen_message($row['user_id'], $_SESSION['user_id'], $connect).'</td>
      <td>
        <form method="post" action="chat.php">
            <input type="hidden" value="'.$row['user_id'].'" name="to_user_id">
            <input type="hidden" value="'.$row['username'].'" name="to_username">
            <input type="submit" value="Start Chat" class="btn btn-info btn-xs start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['username'].'">
        </form>
      </td>
    </tr>
    ';
  }

  $output .= '</table>';

  echo $output;
} else {
  header('Location: index.php');
}

?>
