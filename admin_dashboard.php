<?php
    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Function to create a new course section
    function createCourseSection() {
        // Retrieve form data
        $course_id = $_POST['course_id'];
        $section_id = $_POST['section_id'];
        $semester = $_POST['semester'];
        $year = $_POST['year'];
        $instructor_id = $_POST['instructor_id'];
        $classroom_id = $_POST['classroom_id'];
        $time_slot_id = $_POST['time_slot_id'];

        // Establish connection to your database
        $myconnection = mysqli_connect('localhost', 'root', '');

        $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');

        // Insert new course section into the Section table
        $sql = "INSERT INTO section (course_id, section_id, semester, year, instructor_id, classroom_id, time_slot_id) 
        VALUES ('$course_id', '$section_id', '$semester', '$year', '$instructor_id', '$classroom_id', '$time_slot_id')";

        // Perform the query
        $result = mysqli_query($myconnection, $sql);

        // Check if the query was successful
        if ($result === false) {
            // Query failed
            $errorMessage = "Query Failed: " . mysqli_error($myconnection);
            
            // Optionally, log the error
            error_log($errorMessage);
            
            // Send a response to the client
            http_response_code(500); // Internal Server Error
            echo "An error occurred. Please try again later.";
        } else {
            // Query was successful
            echo "Query executed successfully.";
        }

        mysqli_close($myconnection);

    }

    // Check if the button to create section is clicked
    if(isset($_POST['create_section'])) {
        // Call the function to create a new course section
        createCourseSection();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome Admin</h1>
    <p>Email: <?php echo $admin_email; ?></p>

    <!-- Form to create a new course section and appoint an instructor -->
    <h2>Create New Course Section</h2>
    <form method="post">
        <label for="course_id">Course ID:</label>
        <input type="text" id="course_id" name="course_id" required><br>
        <label for="section_id">Section ID:</label>
        <input type="text" id="section_id" name="section_id" required><br>
        <label for="semester">Semester:</label>
        <input type="text" id="semester" name="semester" required><br>
        <label for="year">Year:</label>
        <input type="number" id="year" name="year" required><br>
        <label for="instructor_id">Instructor ID:</label>
        <input type="text" id="instructor_id" name="instructor_id" required><br>
        <label for="classroom_id">Classroom ID:</label>
        <input type="text" id="classroom_id" name="classroom_id" required><br>
        <label for="time_slot_id">Time Slot ID:</label>
        <input type="text" id="time_slot_id" name="time_slot_id" required><br>
        <button type="submit" name="create_section">Create Section</button>
    </form>
</body>
</html>