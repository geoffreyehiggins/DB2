<?php
    session_start();
    $email = $_SESSION['email'];
    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());

    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');
    $total_credits = 0;
    $totalCredits = 0;
    $totalGradePoints = 0;
    $courseCount = 0;
    $query = "SELECT * FROM course";
    $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));
    $query2 = "SELECT student_id FROM student WHERE email = '$email'";
    $result2 = mysqli_query($myconnection,$query2) or die("Query Failed: " . mysqli_error($myconnection));
    $student_id = mysqli_fetch_assoc($result2);
    $student_id = $student_id['student_id'];
    $_SESSION['student_id'] = $student_id;
    $query3 = "SELECT take.course_id, take.year, take.semester, take.grade, course.course_name, course.credits
           FROM take
           INNER JOIN course ON take.course_id = course.course_id
           WHERE take.student_id = '$student_id'";
    $result3 = mysqli_query($myconnection, $query3) or die ("Query Failed: " . mysqli_error($myconnection));
    
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

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome to the Dashboard</h2>
    <p>Email: <?php echo $email; ?></p>
    <p>ID: <?php echo $student_id; ?></p>

    <h3>Change Password</h3>
    <form action="password_change.php" method="post">
        <label for="current_password">Current Password:</label><br>
        <input type="password" id="current_password" name="current_password"><br>
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password"><br><br>
        <input type="submit" value="Change Password">
    </form>
    <br>
    <h2> Taken Courses </h2>
    <table border="1">
        <tr>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Year</th>
            <th>Semester</th>
            <th>Grade</th>
            <th>Credits</th> 
        </tr>
        
        <?php
        if (mysqli_num_rows($result3) > 0) {
            while ($row = mysqli_fetch_assoc($result3)) {
                $total_credits += $row['credits'];
                $courseCount ++;
                $gradePoints = convertLetterGradeToGPA($row['grade']);
                $totalGradePoints += $gradePoints;
                // Output each row as a table row
                echo '<tr>';
                echo '<td>' . $row['course_id'] . '</td>';
                echo '<td>' . $row['course_name'] . '</td>';
                echo '<td>' . $row['year'] . '</td>';
                echo '<td>' . $row['semester'] . '</td>';
                echo '<td>' . $row['grade'] . '</td>';
                echo '<td>' . $row['credits'] . '</td>';
                echo '</tr>';
            }
        }
        
        ?>
    </table>
    <h2> Credits and GPA </h2>
    <?php
        echo 'Total Credits Taken: ' . $total_credits . '<br/>';
        $averageGPA = ($courseCount > 0) ? ($totalGradePoints / $courseCount) : 0;
        echo 'Current GPA: ' . $averageGPA;
    ?>
    <h2> Available Courses </h2>
    <form method = "post">
    <table border="1">
        <tr>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Credits</th> 
        </tr>

        <?php
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$row['course_id']}</td>";
                echo "<td>{$row['course_name']}</td>";
                echo "<td>{$row['credits']}</td>";
                echo "<td><a href='class_registration.php?course_id={$row['course_id']}'>Register</a></td>";
                echo "</tr>";
            }
        ?>
    </table>
    </form>
</body>
</html>
