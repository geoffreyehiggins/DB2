<?php

$email = $_POST['email'];
$password = $_POST['password'];

// Establish connection to your database
$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());

$mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

$query = "SELECT * FROM account WHERE email = '$email'";
$result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));

if(mysqli_num_rows($result) == 0) {
    echo 'No matching email';
} else {
    $row = mysqli_fetch_assoc($result);
    if($row['password'] != $password) {
        echo 'Incorrect Password';
    }  else {
            if($row['type'] == "undergraduate" || $row['type'] == "masters" || $row['type'] == "phd") {
                echo 'student';
                return "student";
            } else if($row['type'] == "instructor") {
                echo 'instructor';
                return "instructor";
            }
    }
}

// Close connection
mysqli_close($myconnection);

?>