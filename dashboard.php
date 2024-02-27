<?php
    session_start();
    $email = $_SESSION['email'];
    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());

    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle registration logic here
        $courseId = $_POST['course_id'];
        // Implement your registration logic, for example, insert into a registration table
        echo "Registered for Course ID: $courseId";
        exit;
    }
    $query = "SELECT * FROM course";
    $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome to the Dashboard</h2>
    <p>Email: <?php echo $email; ?></p>

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
                echo "<td><button type='submit' name='course_id' value='{$row['course_id']}'>Register</button></td>";
                echo "</tr>";
            }
        ?>
    </table>
    </form>
</body>
</html>
