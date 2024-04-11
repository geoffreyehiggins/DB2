<?php

$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
    // $courseId = $_GET['course_id'];
    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');
    
    #query to print all currently available courses
    $query = "SELECT * FROM section 
              WHERE semester = 'Fall' AND year = 2023";
    $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);

    mysqli_close($myconnection);

?>