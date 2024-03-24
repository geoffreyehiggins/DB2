<?php
    session_start();
    $student_id = $_SESSION['student_id'];
    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
    $courseId = $_GET['course_id'];
    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');
    
    #query to print all currently available courses
    $query = "SELECT * FROM section WHERE course_id = '$courseId'";
    $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));
   
   
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['register'])) {
            $counter = $_POST['register'];
            $sectionId = $_POST["section_id_{$counter}"];
            $semester = $_POST["semester_{$counter}"];
            $year = $_POST["year_{$counter}"];             
            
            #Query to check how many people enrolled
            $qEnrollmentCount = "SELECT COUNT(*) AS enrolled_count FROM take 
            WHERE section_id = '$sectionId' AND semester = '$semester' AND year = '$year'";
            $resultEnrollmentCount = mysqli_query($myconnection, $qEnrollmentCount) or die("Query Failed: " . mysqli_error($myconnection));
            $enrollmentCount = mysqli_fetch_assoc($resultEnrollmentCount)['enrolled_count'];
            #Check prereq
            $qPrereq = "SELECT prereq_id FROM prereq WHERE course_id = '$courseId'";
            $PrereqResult = mysqli_query($myconnection, $qPrereq) or die ("Query Failed: " . mysqli_error($myconnection));
            if (mysqli_num_rows($PrereqResult) > 0)
            {
                $prereq_course_id = mysqli_fetch_assoc($PrereqResult)['prereq_id'];
                $qTookPrereq = "SELECT * FROM take WHERE course_id = '$prereq_course_id' AND student_id = '$student_id' AND grade > 50";
                $rTookPrereq = mysqli_query($myconnection, $qTookPrereq) or die("Query Failed: " . mysqli_error($myconnection));
            }
            $qEnrollmentCountSemester = "SELECT COUNT(*) AS semester_enrolled_count 
                                         FROM take 
                                         WHERE student_id = '$student_id' AND semester = '$semester' AND year = '$year'";
            $rEnrollementCountSemester = mysqli_query($myconnection, $qEnrollmentCountSemester) or die ("Query Failed: " . mysqli_error($myconnection));
            $enrollmentCountSemester = mysqli_fetch_assoc($rEnrollementCountSemester)['semester_enrolled_count'];
            //If statement that checks if prereqs are NOT met otherwise can move on
            
            if($enrollmentCountSemester >= 2)
            {
                echo "You are enrolled in 2 or more courses already this semester.";
            }
            else{
                if((mysqli_num_rows($PrereqResult) > 0) && (mysqli_num_rows($rTookPrereq) <= 0))
                {
                    echo "You do not meet the prerequistes for this course.  Registration Failed.";
                }
                else{
                    if ($enrollmentCount >= 15) {
                        echo "Section is already full. Cannot enroll more students.";
                    } 
                    else {
                        #Query to check if specific student is already enrolled
                        $qcheckdup = "SELECT student_id FROM take 
                                WHERE student_id = '$student_id' AND course_id = '$courseId' AND semester = '$semester' AND year = '$year'";
                        $check = mysqli_query($myconnection, $qcheckdup) or die("Query Failed: ". mysqli_error($myconnection));
                        if(mysqli_num_rows($check) > 0)
                        {
                            echo "Already Enrolled in this course";
                        }
                        else{
                            $query2 = "INSERT INTO take(course_id, section_id, semester, year, student_id) 
                                    VALUES ('$courseId', '$sectionId', '$semester', '$year', '$student_id')";
                            $result2 = mysqli_query($myconnection, $query2) or die("Query Failed: " . mysqli_error($myconnection));
                            echo "Successfully Enrolled in Section";
                        }
                    }
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <h2>Registration</h2>
    <p>ID: <?php echo $student_id; ?></p>
    <form method = "post">
    <table border="1">
        <tr>
            <th>Course ID</th>
            <th>Section ID</th>
            <th>Semester</th>
            <th>Year</th>
            <th>Instructor ID</th> 
            <th>Classroom ID</th>
            <th>Time Slot ID</th>
        </tr>

        <?php
            $counter = 0;
            while($row = mysqli_fetch_assoc($result)){
                $counter++;
                echo "<tr>";
                echo "<td>{$row['course_id']}</td>";
                echo "<td>{$row['section_id']}</td>";
                echo "<td>{$row['semester']}</td>";
                echo "<td>{$row['year']}</td>";
                echo "<td>{$row['instructor_id']}</td>";
                echo "<td>{$row['classroom_id']}</td>";
                echo "<td>{$row['time_slot_id']}</td>";
                echo "<td>
                    <input type='hidden' name='section_id_{$counter}' value='{$row['section_id']}'>
                    <input type='hidden' name='semester_{$counter}' value='{$row['semester']}'>
                    <input type='hidden' name='year_{$counter}' value='{$row['year']}'>
                    <button type='submit' name='register' value='{$counter}'>Register</button></td>";
                echo "</tr>";
            }
        ?>
    </table>
    </form>
</body>
</html>