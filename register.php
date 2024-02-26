<?php
// Retrieve form data
$email = $_POST['email'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$name = $_POST['name'];
$student_id = $_POST['id'];
$type = $_POST['type'];

// Check if password matches confirm password
if ($password != $cpassword) {
    echo "Passwords do not match.";
    exit();
}

// Establish connection to your database
$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());

$mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

// Insert into account table
$sql_account = "INSERT INTO account (email, password, type) VALUES ('$email', '$password', '$type')";

if (mysqli_query($myconnection, $sql_account)) {
    echo "Account created successfully.";
} else {
    echo "Error: " . $sql_account . "<br>" . mysqli_error($myconnection);
    exit();
}

// Insert into student table
$sql_student = "INSERT INTO student (student_id, name, email) VALUES ('$student_id', '$name', '$email')";
if (mysqli_query($myconnection, $sql_student)) {
    switch ($type) {
        case 'undergraduate':
            $sql_undergraduate = "INSERT INTO undergraduate (student_id) VALUES ('$student_id')";
            if (mysqli_query($myconnection, $sql_undergraduate)) {
                echo "Inserted into undergraduate table successfully.";
            } else {
                echo "Error: " . $sql_undergraduate . "<br>" . mysqli_error($myconnection);
            }
            break;
        case 'master':
            $sql_master = "INSERT INTO master (student_id) VALUES ('$student_id')";
            if (mysqli_query($myconnection, $sql_master)) {
                echo "Inserted into master table successfully.";
            } else {
                echo "Error: " . $sql_master . "<br>" . mysqli_error($myconnection);
            }
            break;
        case 'phd':
            $sql_phd = "INSERT INTO PhD (student_id) VALUES ('$student_id')";
            if (mysqli_query($myconnection, $sql_phd)) {
                echo "Inserted into phd table successfully.";
            } else {
                echo "Error: " . $sql_phd . "<br>" . mysqli_error($myconnection);
            }
            break;
        default:
            echo "Invalid student type.";
            break;
    }
} else {
    echo "Error: " . $sql_student . "<br>" . mysqli_error($myconnection);
}

mysqli_close($myconnection);
?>
