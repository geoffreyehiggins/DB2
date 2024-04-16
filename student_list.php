<?php


    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

    $course_id = $_POST['course_id'];
    $section_id = $_POST['section_id'];
    $semester = $_POST['semester'];
    $year = $_POST['year'];

    // Query to select records from Take table joined with Student table
    $qSelectMerged = "SELECT T.grade, S.name AS name, S.student_id
                      FROM Take T
                      INNER JOIN Student S ON T.student_id = S.student_id
                      WHERE course_id = '$course_id' AND section_id = '$section_id' AND semester = '$semester' AND year = '$year'";
    $result = mysqli_query($myconnection, $qSelectMerged) or die("Query Failed: " . mysqli_error($myconnection));

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        // Fetch the student's courses to calculate GPA
        $student_id = $row['student_id'];
        $query3 = "SELECT grade FROM take WHERE student_id = '$student_id' AND grade IS NOT NULL";
        $result3 = mysqli_query($myconnection, $query3) or die ("Query Failed: " . mysqli_error($myconnection));

        $totalCredits = 0;
        $totalGradePoints = 0;

        // Calculate GPA
        while ($course = mysqli_fetch_assoc($result3)) {
            $totalCredits += 1; // All classes taken are worth 3 credits
            $totalGradePoints += convertLetterGradeToGPA($course['grade']);
        }

        // Calculate and display GPA
        $gpa = ($totalCredits > 0) ? number_format($totalGradePoints / $totalCredits, 2) : "N/A";
        $totalCredits *= 3;

        // Store data for JSON encoding
        $studentData = array(
            'name' => $row['name'],
            'grade' => $row['grade'],
            'credits' => $totalCredits * 3, // Multiply total credits by 3 to get total credit hours
            'gpa' => $gpa
        );
        $data[] = $studentData;

    }


    echo json_encode($data);

    mysqli_close($myconnection);

    function convertLetterGradeToGPA($letterGrade) {
        $gradeMapping = array(
            'A+' => 4.0,
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'C-' => 1.7,
            'D+' => 1.3,
            'D' => 1.0,
            'F' => 0.0
        );
    
        $letterGrade = strtoupper($letterGrade);
    
        // Check if the letter grade exists in the mapping, if not, return 0.0
        if (array_key_exists($letterGrade, $gradeMapping)) {
            return $gradeMapping[$letterGrade];
        } else {
            return 0.0; // Return 0.0 for unknown grades
        }
    }

?>