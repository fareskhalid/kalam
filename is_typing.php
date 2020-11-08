<?php
    include "database_connection.php";
    session_start();
    if (isset($_SESSION['user_id'])){
        $query = "
        SELECT * FROM login 
        WHERE user_id != '".$_SESSION['user_id']."' 
        ";
        $statement = $connect->prepare($query);
        $statement->execute();
        $users = $statement->fetchAll();
        foreach ($users as $usr){
            $out = fetch_is_type_status($usr['user_id'], $connect);
        }
        echo $out;
    } else {
        header('Location: index.php');
    }
?>