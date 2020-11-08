<?php

//fetch_user_chat_history.php

include('database_connection.php');

session_start();

if (isset($_SESSION['user_id'])){

    echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);

} else {
    header('Location: index.php');
}
?>