<?php
    $myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());

    $mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

    $query = "SELECT * FROM course";
    $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Courses:</h2>
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
                echo "</tr>";
            }
        ?>
    </table>
</body>
</html>
<?php
    // Close the connection after displaying data
    mysqli_close($myconnection);
?>
