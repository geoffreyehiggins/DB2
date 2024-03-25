<?php
$myconnection = mysqli_connect('localhost', 'root', '') or die ('Could not connect: ' . mysqli_error());
$mydb = mysqli_select_db($myconnection, 'db2') or die ('Could not select database');

function displayMergedTable($myconnection) {
    // Get section variables for filtering
    $course_id = $_GET['course_id'];
    $section_id = $_GET['section_id'];
    $semester = $_GET['semester'];
    $year = $_GET['year'];

    // Query to select records from Take table joined with Student table
    $qSelectMerged = "SELECT T.student_id, T.course_id AS course_id, T.section_id AS section_id, T.semester AS semester, T.year AS year, T.grade, S.name, S.email, S.dept_name
                      FROM Take T
                      INNER JOIN Student S ON T.student_id = S.student_id";
    $rSelectMerged = mysqli_query($myconnection, $qSelectMerged) or die("Query Failed: " . mysqli_error($myconnection));

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
    echo "<h2>Student Records</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Student ID</th><th>Name</th><th>Grade</th><th>GPA</th><th>Credits</th></tr>";

    // Loop through each record in the result set
    while ($row = mysqli_fetch_assoc($rSelectMerged)) {
        // Apply filtering
        if (trim($row['course_id']) == trim($course_id) 
            && trim($row['section_id']) == trim($section_id) 
            && trim($row['semester']) == trim($semester) 
            && trim($row['year']) == trim($year)) {

            // Initialize variables for GPA calculation
            $totalCredits = 0;
            $totalGradePoints = 0;

            // Fetch the student's courses to calculate GPA
            $query3 = "SELECT grade FROM take WHERE student_id = '{$row['student_id']}' AND grade IS NOT NULL";
            $result3 = mysqli_query($myconnection, $query3) or die ("Query Failed: " . mysqli_error($myconnection));

            // Calculate GPA
            while ($course = mysqli_fetch_assoc($result3)) {
                $totalCredits += 1; // All classes taken are worth 3 credits
                $totalGradePoints += convertLetterGradeToGPA($course['grade']);
            }

            // Calculate and display GPA
            $gpa = ($totalCredits > 0) ? number_format($totalGradePoints / $totalCredits, 2) : "N/A";
            $totalCredits *= 3;

            // Display the row with GPA
            echo "<tr>";
            echo "<td>{$row['student_id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['grade']}</td>";
            echo "<td>{$gpa}</td>";
            echo "<td>{$totalCredits}</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

// Helper function to convert letter grades to GPA
function convertLetterGradeToGPA($letterGrade) {
    $gradeMapping = array(
        'A+' => 4.0,
        'A' => 4.0,
        'A-' => 3.7,
        'B+' => 3.3,
        'B' => 3.0,
        'B-' => 2.7,
        'C+' => 2.3,
        'C' => 2.0,
        'C-' => 1.7,
        'D+' => 1.3,
        'D' => 1.0,
        'F' => 0.0
    );

    $letterGrade = strtoupper($letterGrade);

    // Check if the letter grade exists in the mapping, if not, return 0.0
    if (array_key_exists($letterGrade, $gradeMapping)) {
        return $gradeMapping[$letterGrade];
    } else {
        return 0.0; // Return 0.0 for unknown grades
    }
}


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
