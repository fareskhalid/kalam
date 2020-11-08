<?php
    session_start();
    include "database_connection.php";
    // Check if user loged in
    if(isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] == 'POST'):
        // First Check if they are friends or not and delete friendship
        $stmt = $connect->prepare('SELECT * FROM friends WHERE user_id = ? AND friend_id = ?');
        $stmt->execute(array($_SESSION['user_id'], $_POST['block_uid']));
        $count = $stmt->rowCount();
        if($count > 0){
            // There is a friendship
            $stmt2 = $connect->prepare('UPDATE `friends` SET `block_state`= 1 WHERE friend_id = ? AND user_id = ?');
            $stmt2->execute(array($_POST['block_uid'], $_SESSION['user_id']));
        } else {
            $stmt2 = $connect->prepare('INSERT INTO `friends`(`user_id`, `friend_id`, `block_state`) VALUES (?, ?, 1)');
            $stmt2->execute(array($_SESSION['user_id'], $_POST['block_uid']));
        }

        // Unblock User
        if(isset($_POST['unblock_uid'])):
            $stmt = $connect->prepare('SELECT * FROM friends WHERE user_id = ? AND friend_id = ?');
            $stmt->execute(array($_SESSION['user_id'], $_POST['unblock_uid']));
            $count = $stmt->rowCount();
            $check = $stmt->fetch();
            // check if they are blocked or not
            if ($count > 0 && $check['block_state'] == 1){
                // Unblock the user And Remove the friendship
                $stmt2 = $connect->prepare('DELETE FROM `friends` WHERE friend_id = ? AND user_id = ?');
                $stmt2->execute(array($_POST['unblock_uid'], $_SESSION['user_id']));
            }
        endif;
    else:
        header('Location: index.php');
        exit();
    endif;