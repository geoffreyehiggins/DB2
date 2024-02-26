<?php
session_start();

$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];

// Establish connection to your database
$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
$mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

$email = $_SESSION['email'];

// Check if current password matches
$query = "SELECT password FROM account WHERE email = '$email'";
$result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));

if(mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $stored_password = $row['password'];
    
    if($current_password === $stored_password) {
        // Update password
        $update_query = "UPDATE account SET password = '$new_password' WHERE email = '$email'";
        if(mysqli_query($myconnection, $update_query)) {
            echo "Password updated successfully.";
        } else {
            echo "Error updating password: " . mysqli_error($myconnection);
        }
    } else {
        echo "Incorrect current password.";
    }
} else {
    echo "User not found.";
}

// Close connection
mysqli_close($myconnection);
?>
