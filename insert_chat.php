<?php

//insert_chat.php

include('database_connection.php');

session_start();

if (isset($_SESSION['user_id'])){

    $data = array(
    ':to_user_id'     => $_POST['to_user_id'],
    ':from_user_id'   => $_SESSION['user_id'],
    ':chat_message'   => filter_var($_POST['chat_message'], FILTER_SANITIZE_STRING),
    ':status'         => '1'
    );

    $query = "
    INSERT INTO chat_message
    (to_user_id, from_user_id, chat_message, status)
    VALUES (:to_user_id, :from_user_id, :chat_message, :status)
    ";

    $statement = $connect->prepare($query);

    if($statement->execute($data)){
    echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);
    }
} else {
    header('Location: index.php');
}
?>
