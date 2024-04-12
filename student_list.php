<?php


    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

    $course_id = $_POST['course_id'];
    $section_id = $_POST['section_id'];
    $semester = $_POST['semester'];
    $year = $_POST['year'];

    // Query to select records from Take table joined with Student table
    $qSelectMerged = "SELECT T.grade, S.name AS name
                      FROM Take T
                      INNER JOIN Student S ON T.student_id = S.student_id
                      WHERE course_id = '$course_id' AND section_id = '$section_id' AND semester = '$semester' AND year = '$year'";
    $result = mysqli_query($myconnection, $qSelectMerged) or die("Query Failed: " . mysqli_error($myconnection));

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);

    mysqli_close($myconnection);

?>