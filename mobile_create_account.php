<?php

$email = $_POST['email'];
$password = $_POST['password'];

// Establish connection to your database
$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());

$mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

$query = "INSERT INTO account (`email`, `password`, `type`) VALUE ('$email', '$password', 'student')";


$result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));

if($result === false) {
    echo 'failure';
    return 'failure';
} else {
    echo 'success';
    return "success";
}

// Close connection
mysqli_close($myconnection);

?>