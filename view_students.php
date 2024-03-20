<?php
$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
$mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

function displayMergedTable($myconnection) {
    // Query to select records from Take table joined with Student table
    $qSelectMerged = "SELECT T.student_id, T.course_id AS course_id, T.section_id AS section_id, T.semester AS semester, T.year AS `year`, T.grade, S.name, S.email, S.dept_name
                      FROM Take T
                      INNER JOIN Student S ON T.student_id = S.student_id";
    $rSelectMerged = mysqli_query($myconnection, $qSelectMerged) or die("Query Failed: " . mysqli_error($myconnection));

    // Get section variables for filtering
    $course_id = $_GET['course_id'];
    $section_id = $_GET['section_id'];
    $semester = $_GET['semester'];
    $year = $_GET['year'];

    // Check if the query was successful
    if ($rSelectMerged === false) {
        // Query failed
        $errorMessage = "Query Failed: " . mysqli_error($myconnection);
        
        // Optionally, log the error
        error_log($errorMessage);
        
        // Send a response to the client
        http_response_code(500); // Internal Server Error
        echo "An error occurred. Please try again later.";
        return;
    }

    // Display merged table
    echo "";
    echo "<h2>Merged Table</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Student ID</th><th>Name</th><th>Grade</th></tr>";
    while ($row = mysqli_fetch_assoc($rSelectMerged)) {
        // Apply filtering
        echo $row['course_id'][0];
        // echo $row['section_id'];
        // echo $row['semester'];
        //echo $row['year'][0];
        //echo $row['year'][1];
        //echo $row['year'][2];
        //echo $row['year'][3];
        //foreach($val as &$row['year']) {
        //    echo $val;
        //}


        if ($row['course_id'] == $course_id && $row['section_id'] == $section_id && $row['semester'] == $semester && $row['year'] == $year) {
            echo "<tr>";
            echo "<td>{$row['student_id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['grade']}</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

echo "Debugging:<br>";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Call the function to display merged table
    displayMergedTable($myconnection);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Show Tables</title>
</head>
<body>
    <h2>Show Tables</h2>
    
    <!-- Form with a button -->
    <form method="post">
        <button type="submit" name="submit">Show Tables</button>
    </form>
</body>
</html>
