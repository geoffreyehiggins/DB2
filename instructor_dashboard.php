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
    
        // Check if the student already has advisors
        $check_existing_advisor_query = "SELECT COUNT(*) AS advisor_count 
                                         FROM Advise 
                                         WHERE student_id = '$student_id'";
        $existing_advisor_result = mysqli_query($myconnection, $check_existing_advisor_query);
        $existing_advisor_count = mysqli_fetch_assoc($existing_advisor_result)['advisor_count'];
        

        if ($existing_advisor_count == 1 || $existing_advisor_count == 2) {
                // Student already has maximum advisors
                // Check if the advisor is already appointed for the student
            $check_existing_advisor_query = "SELECT * 
                                            FROM Advise 
                                            WHERE student_id = '$student_id' 
                                            AND instructor_id = '$advisor_instructor_id'";
            $existing_advisor_result = mysqli_query($myconnection, $check_existing_advisor_query);
            $assigned_advisor_count = mysqli_num_rows($existing_advisor_result);

            if ($assigned_advisor_count > 0) {
                // Advisor already appointed for the student, update the record
                $update_advisor_query = "UPDATE Advise 
                                        SET start_date = '$advisor_start_date', end_date = " . ($advisor_end_date ? "'$advisor_end_date'" : "NULL") . " 
                                        WHERE student_id = '$student_id' AND instructor_id = '$advisor_instructor_id'";
                $update_result = mysqli_query($myconnection, $update_advisor_query);
        
                if ($update_result === false) {
                    // Update failed
                    $errorMessage = "Failed to update advisor: " . mysqli_error($myconnection);
                    mysqli_rollback($myconnection);
                    error_log($errorMessage);
                    http_response_code(500); // Internal Server Error
                    echo "An error occurred while updating advisor. Please try again later.";
                } else {
                    // Update successful
                    mysqli_commit($myconnection);
                    echo "Advisor updated successfully.";
                }
        
                mysqli_close($myconnection);
                return;
            } 
        } 
        if($existing_advisor_count >= 2) {
            mysqli_rollback($myconnection);
            echo "The student already has the maximum number of advisors (2).";
            mysqli_close($myconnection);
            return;
        }
    
    
        // Insert new advisor record into Advise table
        $insert_advisor_query = "INSERT INTO Advise (instructor_id, student_id, start_date, end_date) 
                                VALUES ('$advisor_instructor_id', '$student_id', '$advisor_start_date', " . ($advisor_end_date ? "'$advisor_end_date'" : "NULL") . ")";
        $advisor_result = mysqli_query($myconnection, $insert_advisor_query);
    
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

    <table border="1">
        <tr>
            <th>Student ID</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>

        <?php
        // Query to retrieve advised students
        $qGetAdvisedStudents = "SELECT student_id, start_date, end_date 
                                FROM Advise 
                                WHERE instructor_id = '$instructor_id'";
        $rGetAdvisedStudents = mysqli_query($myconnection, $qGetAdvisedStudents) or die("Query Failed: " . mysqli_error($myconnection));

        // Display advised students
        while ($row = mysqli_fetch_assoc($rGetAdvisedStudents)) {
            echo "<tr>";
            echo "<td>{$row['student_id']}</td>";
            echo "<td>{$row['start_date']}</td>";
            echo "<td>{$row['end_date']}</td>";
            echo "</tr>";
        }
        ?>
    </table>

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