<?php
$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
$mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

$course_id = $_GET['course_id'];
$section_id = $_GET['section_id'];
$semester = $_GET['semester'];
$year = $_GET['year'];

//Query that retrieves student_id, grade, and name
$qGetStudentInfo = "SELECT t.student_id, t.grade, s.name
                    FROM take t
                    INNER JOIN student s ON t.student_id = s.student_id
                    WHERE t.course_id = '$course_id' AND t.section_id = '$section_id' 
                    AND t.semester = '$semester' AND t.year = '$year'";
$rGetStudentInfo = mysqli_query($myconnection, $qGetStudentInfo) or die("Query Failed: " . mysqli_error($myconnection));
$rows = mysqli_fetch_all($rGetStudentInfo, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
</head>
<body>
    <h2>Students Enrolled in <?php echo $section_id; ?> of Course <?php echo $course_id; ?></h2>
    
    <table border="1">
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Grade</th> 
        </tr>
        <?php
        
        while($row = mysqli_fetch_assoc($rGetStudentInfo)){
            echo "<tr>";
            echo "<td>{$row['student_id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['grade']}</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>