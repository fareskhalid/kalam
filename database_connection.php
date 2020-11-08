<?php

  //database_connection.php

  $connect = new PDO("mysql:host=localhost;dbname=chat", "root", "");

  date_default_timezone_set('Africa/Cairo');

  function fetch_user_last_activity($user_id, $connect){
  $query = "
  SELECT * FROM login_details 
  WHERE user_id = '$user_id' 
  ";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetch();
  return $result['last_activity'];
  }
  function fetch_user_chat_history($from_user_id, $to_user_id, $connect){
  $query = "
  SELECT * FROM chat_message 
  WHERE (from_user_id = '".$from_user_id."' 
  AND to_user_id = '".$to_user_id."') 
  OR (from_user_id = '".$to_user_id."' 
  AND to_user_id = '".$from_user_id."') 
  ORDER BY timestamp ASC
  ";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $output = '<ul class="list-unstyled ch-box">';
  foreach($result as $row){
    $user_name = '';
    if($row["from_user_id"] == $from_user_id) {
    $user_name = '<b class="text-success sender">You</b>';
    $owner = "you";
    } else {
    $user_name = '<b class="text-danger receiver">'.get_user_name($row['from_user_id'], $connect).'</b>';
    $owner = "rec";
    }
    $output .= '
    <li>' . $user_name. '
    <p class="msg ' . $owner . '" data-msgid="' . $row["chat_message_id"] .'">'.$row["chat_message"].'
      <span align="right" class="time">
        <small><em class="' . $owner . '">'.$row['timestamp'].'</em></small>
      </span>
    </p>
    <div class="clear-fix"></div>
    </li>
    ';
  }
  $output .= '</ul>';
  return $output;
  }

  function get_user_name($user_id, $connect){
  $query = "SELECT username FROM login WHERE user_id = '$user_id'";
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  foreach($result as $row) {
    return $row['username'];
  }
  }

  function count_unseen_message($from_user_id, $to_user_id, $connect){
  $query = "
  SELECT * FROM chat_message 
  WHERE from_user_id = '$from_user_id' 
  AND to_user_id = '$to_user_id' 
  AND status = '1'
  ";
  $statement = $connect->prepare($query);
  $statement->execute();
  $count = $statement->rowCount();
  $output = '';
  if($count > 0) {
    $output = '<span class="label label-success">'.$count.'</span>';
  }
  return $output;
  }

  function fetch_is_type_status($user_id, $connect) {
  $query = "
  SELECT is_type FROM login_details 
  WHERE user_id = '".$user_id."' 
  ORDER BY last_activity DESC 
  LIMIT 1
  "; 
  $statement = $connect->prepare($query);
  $statement->execute();
  $result = $statement->fetchAll();
  $output = '';
  foreach($result as $row) {
    if($row["is_type"] == 'yes'){
    $output = '  <small><em><span class="text-muted">Typing...</span></em></small>';
    }
  }
  return $output;
  }

?>
