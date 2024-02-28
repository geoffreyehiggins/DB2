<?php
    session_start();
    $email = $_SESSION['email'];
    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());

    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

    $query = "SELECT * FROM course";
    $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));
    $query2 = "SELECT student_id FROM student WHERE email = '$email'";
    $result2 = mysqli_query($myconnection,$query2) or die("Query Failed: " . mysqli_error($myconnection));
    $student_id = mysqli_fetch_assoc($result2);
    $_SESSION['student_id'] = $student_id;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome to the Dashboard</h2>
    <p>Email: <?php echo $email; ?></p>
    <p>ID: <?php echo $student_id['student_id']; ?></p>

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
            <th>Credits</th> 
        </tr>
    </table>
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
