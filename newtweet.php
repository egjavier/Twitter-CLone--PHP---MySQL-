<?php

  session_start();

  // DATABASE CREDENTIAL
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'twitter';

  // CONNECT TO DATABASE
    $conn = new mysqli($host, $username, $password, $database);

  // CHECK CONNECTION
    if($conn -> connect_error) {
      echo 'Database connection failed.';
    }

  // CHECK IF THERE IS A POST REQUEST
    if($_SERVER['REQUEST_METHOD'] == "POST") {

      $body = $_POST['tweet'];
      $user_id = $_SESSION['user_id'];
  
      // ADD INFO TO THE DATABASE
        $sql = $conn -> prepare("INSERT INTO tweets (body, user_id) VALUES (?, ?)");
        $sql -> bind_param("ss", $body, $user_id);
  
      // IF ALL INFO ARE CORRECT
        if($sql -> execute()) {
          header("Location: index.php");
        } 
    }

?>