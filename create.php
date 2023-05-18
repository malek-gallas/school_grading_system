<?php

// Start the session
session_start();
if(($_SESSION['account']) != 'teacher'){
    header('Location: login.php');
    exit();
}
// Connect to database
require_once 'database\connect.php';
$conn = connect();
if(!$conn) die("Connection Failed !") ;

if (isset($_POST['submit'])){
    // Get the URL data
    $teacher_id= $_GET['teacher_id'];
    // Get the form data
    $course_name = $_POST['course_name'];
    // Prepare the statement
    $req = 'INSERT INTO courses(course_name, teacher_id) 
    VALUES(:course_name, :teacher_id)';
    $statement = $conn->prepare($req);
    // Bind & Execute
    $statement->execute([
    ':course_name' => $course_name,
    ':teacher_id' => $teacher_id
    ]);
    // Get the course_id of the newly inserted record
    $course_id = $conn->lastInsertId();
    // Loop through the submitted enroll values and insert records in the grades table
    foreach ($_POST['enroll'] as $student_id) {
        $req = 'INSERT INTO grades(course_id, student_id, tp, ds, ex) 
        VALUES(:course_id, :student_id, 0, 0, 0)';
        $statement = $conn->prepare($req);
        $statement->execute([
            ':course_id' => $course_id,
            ':student_id' => $student_id
        ]);
    }
    // Redirection
    echo '<script>alert("Course Added !"); window.location.href = "index.php";</script>';
}
?>

<?php include 'header.php'; ?>
    <div class="container">
        <div class="text-center mb-4">
            <h3>Add new course</h3>
            <p class="text-muted">
                Please fill the course details !
            </p>
        </div>
        <div class="container">
            <form action="" method="post">

                <div class="row mb-3">
                    <label class="form-label">Course Name :</label>
                    <input type="text" class="form-control" name="course_name" placeholder="Course Name" required>
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
                        $students_request= "SELECT * FROM students ";
                        $students_response= $conn->query($students_request);
                        $students= $students_response->fetchAll(PDO::FETCH_ASSOC);
                        foreach($students as $student){
                            ?>
                                <tr>
                                <td> <?php echo $student['student_id']?> </td>
                                <td> <?php echo $student['first_name']?> </td>
                                <td> <?php echo $student['last_name']?> </td>
                                <td> 
                                    <input type="checkbox" name="enroll[]" value="<?php echo $student['student_id']?>"> 
                                </td>
                                </tr>
                            <?php
                            }
                    ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-success mb-3 col-3" name="submit">Add</button>
                </div>
            </form>    
        </div>
    </div>
<?php include 'footer.php' ?>