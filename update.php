<?php

// Connect to database
require_once 'database/connect.php';
$conn = connect();
if (!$conn) die("Connection Failed!");

// Start the session
session_start();
if (!isset($_SESSION['account']) || $_SESSION['account'] !== 'teacher') {
    header('Location: login.php');
    exit();
}

if (isset($_POST['submit'])) {
    $course_id = $_GET['course_id'];
    $course_name = $_POST['course_name'];

    // Prepare Update statement
    $sql = 'UPDATE courses SET course_name = :course_name WHERE course_id = :course_id';
    $statement = $conn->prepare($sql);
    // bind params
    $statement->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $statement->bindParam(':course_name', $course_name);
    // execute the UPDATE statement
    $statement->execute();

    // Loop through all students and update the enrollment status in the grades table
    $enrollments = isset($_POST['enroll']) ? $_POST['enroll'] : array(); // Get the selected enrollments
    $students_request = "SELECT * FROM students";
    $students_response = $conn->query($students_request);
    $students = $students_response->fetchAll(PDO::FETCH_ASSOC);

    foreach ($students as $student) {
        $student_id = $student['student_id'];
        $enrolled = in_array($student_id, $enrollments); // Check if the student is enrolled

        if ($enrolled) {
            // Insert or update record in grades table
            $stmt = $conn->prepare("INSERT INTO grades (course_id, student_id, tp, ds, ex) 
                VALUES (:course_id, :student_id, 0, 0, 0)
                ON DUPLICATE KEY UPDATE tp = :tp, ds = :ds, ex = :ex");
            $stmt->execute(['course_id' => $course_id, 'student_id' => $student_id, 'tp' => 0, 'ds' => 0, 'ex' => 0]);
        } else {
            // Delete record from grades table if it exists
            $stmt = $conn->prepare("DELETE FROM grades WHERE student_id = :student_id AND course_id = :course_id");
            $stmt->execute(['student_id' => $student_id, 'course_id' => $course_id]);
        }
    }

    // Echo a response to the client
    echo '<script>alert("Course Updated !"); window.location.href = "index.php";</script>';
}
?>
<?php include 'header.php'; ?>
<div class="container">
    <div class="text-center mb-4">
        <h3>Add course</h3>
        <p class="text-muted">
            Please fill the course details !
        </p>
    </div>
    <div class="container justify-content-center">
        <form action="" method="post">
            <div class="row mb-3">
                <?php 
                $course_id = $_GET['course_id'];
                $stmt= $conn->prepare("SELECT course_name FROM courses where course_id= :course_id");
                $stmt->bindParam(":course_id", $course_id);
                $stmt->execute();
                $course= $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <label class="form-label">Course Name :</label>
                <input type="text" class="form-control" name="course_name" placeholder="Course Name" required
                value="<?php echo $course['course_name'];?>">
            </div>

            <table class="table table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Student ID</th>
                        <th scope="col">First name</th>
                        <th scope="col">Last name</th>
                        <th scope="col">Enroll</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //Prpare
                    $students_request = "SELECT * FROM students ";
                    $students_response = $conn->query($students_request);
                    $students = $students_response->fetchAll(PDO::FETCH_ASSOC);
                    foreach($students as $student){
                        // Check if student is enrolled in the course
                        $stmt = $conn->prepare("SELECT * FROM grades WHERE student_id=:student_id AND course_id=:course_id");
                        $stmt->execute(['student_id' => $student['student_id'], 'course_id' => $course_id]);
                        $enrolled = $stmt->rowCount() > 0;

                        // Display the student details and checkbox
                        ?>
                        <tr>
                            <td> <?php echo $student['student_id']?> </td>
                            <td> <?php echo $student['first_name']?> </td>
                            <td> <?php echo $student['last_name']?> </td>
                            <td> 
                                <input type="checkbox" name="enroll[]" value="<?php echo $student['student_id']?>" <?php echo $enrolled ? 'checked' : '' ?>>
                            </td>
                        </tr> 
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-success mb-3 col-3" name="submit">Update</button>
            </div>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>