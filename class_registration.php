<?php
    $courseId = $_GET['course_id'];
    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());

    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

    $query = "SELECT * FROM section WHERE course_id = '$courseId'";
    $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <h2>Registration</h2>
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
                echo "<td><a href='class_registration.php?course_id={$row['course_id']}'>Register</a></td>";
                echo "</tr>";
            }
        ?>
    </table>
    </form>
</body>
</html>