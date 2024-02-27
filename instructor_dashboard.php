<?php
    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());

    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');
    
    $query = "SELECT course_id, section_id, semester, year FROM section S, instructor I WHERE I.instructor_id = S.instructor_id";
    $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));

    session_start();
    $email = $_SESSION['email'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome to the Instructor Dashboard</h2>
    <p>Email: <?php echo $email; ?></p>

    <h3>Change Password</h3>
    <form action="password_change.php" method="post">
        <label for="current_password">Current Password:</label><br>
        <input type="password" id="current_password" name="current_password"><br>
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password"><br>
        <input type="submit" value="Change Password">
    </form>
    <h3>Courses you are teaching</h3>
    <table border="1">
        <tr>
            <th>Course ID</th>
            <th>Section ID</th>
            <th>Semester</th> 
            <th>Year</th> 
        </tr>

        <?php
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$row['course_id']}</td>";
                echo "<td>{$row['section_id']}</td>";
                echo "<td>{$row['semester']}</td>";
                echo "<td>{$row['year']}</td>";
                echo "</tr>";
                //test
            }
        ?>
    </table>

    <h3>PhD students</h3>
</body>
</html>