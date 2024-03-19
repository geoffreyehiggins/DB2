<?php
    session_start();
    $admin_email = $_SESSION['email'];
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

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
    
        // Retrieve existing time slots taught by the instructor
        $existing_slots_query = "SELECT time_slot_id 
                                 FROM section 
                                 WHERE instructor_id = '$instructor_id' 
                                 AND semester = '$semester' 
                                 AND year = '$year'";
        $existing_slots_result = mysqli_query($myconnection, $existing_slots_query);
        $existing_slots = [];
        while ($row = mysqli_fetch_assoc($existing_slots_result)) {
            $existing_slots[] = $row['time_slot_id'];
        }
    
        // Define consecutive pairs of time slots
        $consecutive_pairs = array(
            'TS1' => 'TS2',
            'TS2' => 'TS3',
            'TS4' => 'TS5'
            // Add more pairs as needed
        );
    
        // Check if the new time slot is consecutive with any existing time slots
        $consecutive = false;
        foreach ($existing_slots as $slot) {
            if (isset($consecutive_pairs[$slot]) && $consecutive_pairs[$slot] === $time_slot_id) {
                $consecutive = true;
                break;
            }
        }
    
        if (!$consecutive) {
            // The new time slot is not consecutive with any existing time slots
            echo "The new section must have a consecutive time slot with existing sections.";
            return;
        }
    
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

    function assignTAToSection() {
        // Retrieve form data
        $student_id = $_POST['student_id'];
        $course_id = $_POST['course_id'];
        $section_id = $_POST['section_id'];
        $semester = $_POST['semester'];
        $year = $_POST['year'];
    
        // Establish connection to your database
        $myconnection = mysqli_connect('localhost', 'root', '');
    
        $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
    
        // Check if the section has more than 10 students
        $qEnrollmentCount = "SELECT COUNT(*) AS enrolled_count FROM take 
            WHERE section_id = '$section_id' AND semester = '$semester' AND year = '$year'";

        $student_count_result = mysqli_query($myconnection, $qEnrollmentCount);
        $student_count = mysqli_fetch_assoc($student_count_result)['enrolled_count'];
    
        if ($student_count > 10) {
            // Check if the student is already assigned as TA for another section
            $existing_ta_query = "SELECT * 
                                  FROM TA 
                                  WHERE student_id = '$student_id'";
            $existing_ta_result = mysqli_query($myconnection, $existing_ta_query);
            $existing_ta_count = mysqli_num_rows($existing_ta_result);
    
            if ($existing_ta_count == 0) {
                // Insert the student as TA for the section
                $insert_ta_query = "INSERT INTO TA (student_id, course_id, section_id, semester, year) 
                                    VALUES ('$student_id', '$course_id', '$section_id', '$semester', '$year')";
                $result = mysqli_query($myconnection, $insert_ta_query);
    
                if ($result) {
                    echo "Student assigned as TA successfully.";
                } else {
                    echo "Failed to assign student as TA.";
                }
            } else {
                echo "Student is already assigned as TA for another section.";
            }
        } else {
            echo "Section does not have more than 10 students.";
        }
    
        mysqli_close($myconnection);
    }
    
    // Check if the button to create section is clicked
    if(isset($_POST['create_section'])) {
        // Call the function to create a new course section
        createCourseSection();
    }

    // Check if the button to assign TA is clicked
    if(isset($_POST['assign_ta'])) {
        // Call the function to assign TA to section
        assignTAToSection();
    }
    
    function appointAdvisorForPhDStudent() {
        // Retrieve form data
        $advisor_instructor_id = $_POST['advisor_instructor_id'];
        $student_id = $_POST['student_id'];
        $advisor_start_date = $_POST['advisor_start_date'];
        $advisor_end_date = isset($_POST['advisor_end_date']) ? $_POST['advisor_end_date'] : null;
    
        // Establish connection to your database
        $myconnection = mysqli_connect('localhost', 'root', '');
        $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
    
        // Begin transaction
        mysqli_begin_transaction($myconnection);
    
        // Check if the student already has an advisor
        $check_existing_advisor_query = "SELECT COUNT(*) AS advisor_count 
                                         FROM Advise 
                                         WHERE student_id = '$student_id'";
        $existing_advisor_result = mysqli_query($myconnection, $check_existing_advisor_query);
        $existing_advisor_count = mysqli_fetch_assoc($existing_advisor_result)['advisor_count'];
    
        if ($existing_advisor_count > 0) {
            // Student already has an advisor, update existing record in Advise table
            $update_advisor_query = "UPDATE Advise 
                                     SET instructor_id = '$advisor_instructor_id', 
                                         start_date = '$advisor_start_date', 
                                         end_date = " . ($advisor_end_date ? "'$advisor_end_date'" : "NULL") . " 
                                     WHERE student_id = '$student_id'";
            $advisor_result = mysqli_query($myconnection, $update_advisor_query);
        } else {
            // Student does not have an advisor, insert new record into Advise table
            $insert_advisor_query = "INSERT INTO Advise (instructor_id, student_id, start_date, end_date) 
                                    VALUES ('$advisor_instructor_id', '$student_id', '$advisor_start_date', " . ($advisor_end_date ? "'$advisor_end_date'" : "NULL") . ")";
            $advisor_result = mysqli_query($myconnection, $insert_advisor_query);
        }
    
        // Check if the advisor appointment was successful
        if ($advisor_result === false) {
            // Advisor appointment failed
            $errorMessage = "Advisor appointment failed: " . mysqli_error($myconnection);
            
            // Rollback transaction
            mysqli_rollback($myconnection);
            
            // Optionally, log the error
            error_log($errorMessage);
            
            // Send a response to the client
            http_response_code(500); // Internal Server Error
            echo "An error occurred while appointing advisor. Please try again later.";
        } else {
            // Commit transaction
            mysqli_commit($myconnection);
            
            // Advisor appointment successful
            echo "Advisor appointed successfully.";
        }
    
        mysqli_close($myconnection);
    }
    
    
    // Check if the button to appoint advisor is clicked
    if(isset($_POST['appoint_advisor'])) {
        // Call the function to appoint advisor for PhD student
        appointAdvisorForPhDStudent();
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

    <!-- Form to assign TA to a section -->
    <h2>Assign TA to Section</h2>
    <form method="post">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br>
        <label for="course_id_ta">Course ID:</label>
        <input type="text" id="course_id_ta" name="course_id" required><br>
        <label for="section_id_ta">Section ID:</label>
        <input type="text" id="section_id_ta" name="section_id" required><br>
        <label for="semester_ta">Semester:</label>
        <input type="text" id="semester_ta" name="semester" required><br>
        <label for="year_ta">Year:</label>
        <input type="number" id="year_ta" name="year" required><br>
        <button type="submit" name="assign_ta">Assign TA</button>
    </form>

        <!-- Form to appoint instructors as advisors for PhD students -->
    <h2>Appoint Advisor for PhD Student</h2>
    <form method="post">
        <label for="advisor_instructor_id">Instructor ID:</label>
        <input type="text" id="advisor_instructor_id" name="advisor_instructor_id" required><br>
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br>
        <label for="advisor_start_date">Start Date:</label>
        <input type="date" id="advisor_start_date" name="advisor_start_date" required><br>
        <label for="advisor_end_date">End Date (Optional):</label>
        <input type="date" id="advisor_end_date" name="advisor_end_date"><br>
        <button type="submit" name="appoint_advisor">Appoint Advisor</button>
    </form>


</body>
</html>
