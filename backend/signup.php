<?php
require 'db_conn.php';
$registrationError = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = sanitizeInput($_POST['name']);
    $username = sanitizeInput($_POST['uname']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    if (empty($fullName) || empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit();
    }

    $conn = OpenCon();
        if (userExists($conn , $fullName,$username,$email)) {
            echo "User with the provided name , username, emailalready exists. Please choose a different one.";
            exit();
        }
       
    $query = "INSERT INTO users (FULLNAME, USERNAME, EMAIL, PASSWORD) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $fullName, $username, $email, $password);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header("Location: ../index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    CloseCon($conn);
}

function sanitizeInput($input) {
    $sanitizedInput = trim($input);
    $sanitizedInput = stripslashes($sanitizedInput);
    $sanitizedInput = htmlspecialchars($sanitizedInput);
    return $sanitizedInput;
}
function userExists($conn, $username, $fullName, $email) {
    $query = "SELECT * FROM users WHERE USERNAME = ? OR FULLNAME = ? OR EMAIL = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $fullName, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}
?>
