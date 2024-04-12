<?php
    $email = $_POST['email'];
    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

    $query = "SELECT instructor_id FROM instructor WHERE email = '$email'";
    $rGetID = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));
    $instructor_id = mysqli_fetch_assoc($rGetID);
    $instructor_id = $instructor_id['instructor_id'];

    $qGetAllInstructedCourses = "SELECT course_id, section_id, semester, year 
                    FROM section 
                    WHERE instructor_id = '$instructor_id'";
    $qResult = mysqli_query($myconnection, $qGetAllInstructedCourses) or die("Query Failed: " . mysqli_error($myconnection));

    $data = array();
    while ($row = mysqli_fetch_assoc($qResult)) {
        $data[] = $row;
    }

    echo json_encode($data);

    mysqli_close($myconnection);

?>