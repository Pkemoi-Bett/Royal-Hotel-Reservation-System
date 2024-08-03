<?php
//start session
  session_start();



    $host = "localhost";
    $user = "root";
    $pass = "Gyyh1307";
    $db   ="hotel_reservation_system";
    
    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn -> connect_error) 
    {
      die($conn -> error);
    }
    else
    {
      //echo "database connected";
    }
    
    ?>
?>