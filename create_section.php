
    <!-- Display Classroom table -->
    <h2>Classroom Table</h2>
    <?php
        // Retrieve Classroom table data
        $query = "SELECT * FROM Classroom";
        $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));

        // Display Classroom table in a table format
        echo "<table>";
        echo "<tr><th>Classroom ID</th><th>Building</th><th>Room Number</th><th>Capacity</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['classroom_id']."</td>";
            echo "<td>".$row['building']."</td>";
            echo "<td>".$row['room_number']."</td>";
            echo "<td>".$row['capacity']."</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Free result set
        mysqli_free_result($result);
    ?>

    <!-- Display Time Slot table -->
    <h2>Time Slot Table</h2>
    <?php
        // Retrieve Time Slot table data
        $query = "SELECT * FROM Time_Slot";
        $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));

        // Display Time Slot table in a table format
        echo "<table>";
        echo "<tr><th>Time Slot ID</th><th>Day</th><th>Start Time</th><th>End Time</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['time_slot_id']."</td>";
            echo "<td>".$row['day']."</td>";
            echo "<td>".$row['start_time']."</td>";
            echo "<td>".$row['end_time']."</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Free result set
        mysqli_free_result($result);
    ?>

    <!-- Display Course table -->
    <h2>Course Table</h2>
    <?php
        // Retrieve Course table data
        $query = "SELECT * FROM Course";
        $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));

        // Display Course table in a table format
        echo "<table>";
        echo "<tr><th>Course ID</th><th>Course Name</th><th>Credits</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['course_id']."</td>";
            echo "<td>".$row['course_name']."</td>";
            echo "<td>".$row['credits']."</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Free result set
        mysqli_free_result($result);
    ?>

    <!-- Display Section table -->
    <h2>Section Table</h2>
    <?php
        // Retrieve Section table data
        $query = "SELECT * FROM Section";
        $result = mysqli_query($myconnection, $query) or die("Query Failed: " . mysqli_error($myconnection));

        // Display Section table in a table format
        echo "<table>";
        echo "<tr><th>Course ID</th><th>Section ID</th><th>Semester</th><th>Year</th><th>Instructor ID</th><th>Classroom ID</th><th>Time Slot ID</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['course_id']."</td>";
            echo "<td>".$row['section_id']."</td>";
            echo "<td>".$row['semester']."</td>";
            echo "<td>".$row['year']."</td>";
            echo "<td>".$row['instructor_id']."</td>";
            echo "<td>".$row['classroom_id']."</td>";
            echo "<td>".$row['time_slot_id']."</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Free result set
        mysqli_free_result($result);

        // Close connection
        mysqli_close($myconnection);
    ?>
</body>
</html>
