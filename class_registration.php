<?php
    session_start();
    $student_id = $_SESSION['student_id'];
    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
    $courseId = $_GET['course_id'];
    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

    $query = "SELECT * FROM section WHERE course_id = '$courseId'";
    $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['register'])) {
            $sectionId = $_POST['section_id'];
            $semester = $_POST['semester']; // You need to fetch or set the semester data
            $year = $_POST['year']; // You need to fetch or set the year data         
            $student_id = $student_id['student_id'];
            $courseId = trim($courseId);
            $sectionId = trim($sectionId);
            $semester = trim($semester);
            $year = trim($year);
            
            $query2 = "INSERT INTO take(student_id, course_id, section_id, semester, year) 
                        VALUES ('$student_id', '$courseId', '$sectionId', '$semester', '$year')";
            echo "Debugging Query: $query2";
            $result = mysqli_query($myconnection, $query2) or die("Query Failed: " . mysqli_error($myconnection));

            // Add your registration logic here, for example, inserting the user and section into a registrations table.
            // $userId = ...; // Get the user ID from the session or user authentication
            // $query = "INSERT INTO registrations (user_id, section_id) VALUES ('$userId', '$selectedSectionId')";
            // $result = mysqli_query($myconnection, $query);

            // After registration, you can redirect or display a success message.
            // Example redirection:
            // header("Location: success_page.php");
            // exit();
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
    <p>ID: <?php echo $student_id['student_id']; ?></p>
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
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$row['course_id']}</td>";
                echo "<td>{$row['section_id']}</td>";
                echo "<td>{$row['semester']}</td>";
                echo "<td>{$row['year']}</td>";
                echo "<td>{$row['instructor_id']}</td>";
                echo "<td>{$row['classroom_id']}</td>";
                echo "<td>{$row['time_slot_id']}</td>";
                echo "<td>
                    <input type='hidden' name='section_id' value='{$row['section_id']}'>
                    <input type='hidden' name='semester' value='{$row['semester']}'>
                    <input type='hidden' name='year' value='{$row['year']}'>
                    <button type='submit' name='register' value='{$row['section_id']}'>Register</button></td>";
                echo "</tr>";
            }
        ?>
    </table>
    </form>
</body>
</html>