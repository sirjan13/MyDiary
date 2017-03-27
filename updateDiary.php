<?php

    session_start();

    if(array_key_exists("content", $_POST)) {
        
        include 'connect.php';
        
        $query = "UPDATE `users` SET content = '" . mysqli_real_escape_string($link, $_POST['content']) . "' WHERE email = '" . mysqli_real_escape_string($link, $_SESSION['id']) . "' LIMIT 1";
        mysqli_query($link, $query);
        
    }

?>
