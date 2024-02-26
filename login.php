<?php

$email = $_POST['email'];
$password = $_POST['password'];

// Establish connection to your database
$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());

$mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

$query = "SELECT email, password FROM account WHERE email = '$email'";
$result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));

if(mysqli_num_rows($result) == 0) {
    echo 'No matching email';
} else {
    $row = mysqli_fetch_assoc($result);
    if($row['password'] != $password) {
        echo 'Incorrect Password';
    } else {
        echo 'Login successful';
        // Here you can redirect the user to another page or perform other actions upon successful login
    }
}

// Free result set
mysqli_free_result($result);

// Close connection
mysqli_close($myconnection);

?>
