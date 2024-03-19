<?php
$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
$mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

function displayTables($myconnection) {
    // Query to select all records from Take table
    $qSelectTake = "SELECT * FROM Take";
    $rSelectTake = mysqli_query($myconnection, $qSelectTake) or die("Query Failed: " . mysqli_error($myconnection));

    // Check if the query was successful
    if ($rSelectTake === false) {
        // Query failed
        $errorMessage = "Query Failed: " . mysqli_error($myconnection);
        
        // Optionally, log the error
        error_log($errorMessage);
        
        // Send a response to the client
        http_response_code(500); // Internal Server Error
        echo "An error occurred. Please try again later.";
        return;
    }

    // Display Take table
    echo "<h2>Take Table</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Student ID</th><th>Course ID</th><th>Section ID</th><th>Semester</th><th>Year</th><th>Grade</th></tr>";
    while ($row = mysqli_fetch_assoc($rSelectTake)) {
        echo "<tr>";
        echo "<td>{$row['student_id']}</td>";
        echo "<td>{$row['course_id']}</td>";
        echo "<td>{$row['section_id']}</td>";
        echo "<td>{$row['semester']}</td>";
        echo "<td>{$row['year']}</td>";
        echo "<td>{$row['grade']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Query to select all records from Student table
    $qSelectStudent = "SELECT * FROM Student";
    $rSelectStudent = mysqli_query($myconnection, $qSelectStudent) or die("Query Failed: " . mysqli_error($myconnection));

    // Check if the query was successful
    if ($rSelectStudent === false) {
        // Query failed
        $errorMessage = "Query Failed: " . mysqli_error($myconnection);
        
        // Optionally, log the error
        error_log($errorMessage);
        
        // Send a response to the client
        http_response_code(500); // Internal Server Error
        echo "An error occurred. Please try again later.";
        return;
    }

    // Display Student table
    echo "<h2>Student Table</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Student ID</th><th>Name</th><th>Email</th><th>Dept Name</th></tr>";
    while ($row = mysqli_fetch_assoc($rSelectStudent)) {
        echo "<tr>";
        echo "<td>{$row['student_id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['dept_name']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "Debugging:<br>";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Call the function to display tables
    displayTables($myconnection);
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
