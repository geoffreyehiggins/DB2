<?php

    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());

    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');
    
    session_start();
    $email = $_SESSION['email'];
    $qGetID = "SELECT instructor_id FROM instructor WHERE email = '$email'";
    $rGetID = mysqli_query($myconnection, $qGetID) or die("Query Failed: " . mysqli_error($myconnection));
    $instructor_id = mysqli_fetch_assoc($rGetID);
    $instructor_id = $instructor_id['instructor_id'];

    $qGetCurrent = "SELECT course_id, section_id, semester, year 
                    FROM section 
                    WHERE instructor_id = '$instructor_id' AND semester = 'Spring' AND year = 2024";
    $rGetCurrent = mysqli_query($myconnection, $qGetCurrent) or die("Query Failed: " . mysqli_error($myconnection));
    $qGetPast = "SELECT course_id, section_id, semester, year 
                    FROM section 
                    WHERE instructor_id = '$instructor_id' 
                    AND (semester != 'Spring' AND year != 2024)";
    $rGetPast = mysqli_query($myconnection, $qGetPast) or die("Query Failed: " . mysqli_error($myconnection));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome to the Instructor Dashboard</h2>
    <p>Email: <?php echo $email; ?></p>
    <p>ID: <?php echo $instructor_id; ?></p>

    <h3>Change Password</h3>
    <form action="password_change.php" method="post">
        <label for="current_password">Current Password:</label><br>
        <input type="password" id="current_password" name="current_password"><br>
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password"><br>
        <input type="submit" value="Change Password">
    </form>
    <h3>Current Courses:</h3>
    <table border="1">
        <tr>
            <th>Course ID</th>
            <th>Section ID</th>
            <th>Semester</th> 
            <th>Year</th> 
        </tr>

        <?php
            while($row = mysqli_fetch_assoc($rGetCurrent)){
                echo "<tr>";
                echo "<td>{$row['course_id']}</td>";
                echo "<td>{$row['section_id']}</td>";
                echo "<td>{$row['semester']}</td>";
                echo "<td>{$row['year']}</td>";
                echo "<td><a href='view_students.php?course_id=
                        {$row['course_id']}&section_id={$row['section_id']}&semester={$row['semester']}&year={$row['year']}'>
                        View Students</a></td>";
                echo "</tr>";
            }
        ?>
    </table>
    <h3>Past Courses:</h3>
    <table border="1">
        <tr>
            <th>Course ID</th>
            <th>Section ID</th>
            <th>Semester</th> 
            <th>Year</th> 
        </tr>

        <?php
            while($row = mysqli_fetch_assoc($rGetPast)){
                echo "<tr>";
                echo "<td>{$row['course_id']}</td>";
                echo "<td>{$row['section_id']}</td>";
                echo "<td>{$row['semester']}</td>";
                echo "<td>{$row['year']}</td>";
                echo "<td><a href='view_students.php?course_id=
                {$row['course_id']}&section_id={$row['section_id']}&semester={$row['semester']}&year={$row['year']}'>
                View Students</a></td>";
                echo "</tr>";
            }
        ?>
    </table>
    <h3>PhD students</h3>
</body>
</html>