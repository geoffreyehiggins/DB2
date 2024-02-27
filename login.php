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
    } else {
        if ($row['type'] == "admin"){
            echo 'Login successful';
            session_start();
             $_SESSION['email'] = $email;
            header("Location: admin_dashboard.php");
            // route them to type specific dashboard
            exit();
        } elseif ($row['type'] == "instructor"){
            echo 'Login successful';
            session_start();
             $_SESSION['email'] = $email;
            header("Location: instructor_dashboard.php");
            // route them to type specific dashboard
            exit();
        } else {
            echo 'Login successful';
            session_start();
             $_SESSION['email'] = $email;
            header("Location: dashboard.php");
            // route them to type specific dashboard
            exit();
        }
    }
}

// Free result set
mysqli_free_result($result);

// Close connection
mysqli_close($myconnection);

?>
