<?php

    //update_last_activity.php

    include('database_connection.php');

    session_start();

    if (isset($_SESSION['user_id'])){

        $query = "
        UPDATE login_details 
        SET last_activity = now() 
        WHERE user_id = '".$_SESSION["user_id"]."'
        ";

        $statement = $connect->prepare($query);

        if($statement->execute()){
            echo "done";
        };
        
    } else {
        header('Location: index.php');
    }
?>
