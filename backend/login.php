<?php

require 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['uname'];
    $password = $_POST['password'];
    $conn = OpenCon();
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        echo "Login successful!";
    } else {
        header("Location: ../index.php"); 
        exit();
    }

    CloseCon($conn);
}
?>
