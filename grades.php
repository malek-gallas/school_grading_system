<?php
    // Start the session
    session_start();
    if(($_SESSION['account']) != 'teacher'){
        header('Location: login.php');
        exit();
    }
?>
<?php include 'header.php'; ?> 
<?php
    if(isset($_POST['submit'])) {
        // Connect to database
        require_once 'database\connect.php';
        $conn = connect();
        if(!$conn) die("Connection Failed !") ;
        // Get the URL data
        $course_id= $_GET['course_id'];
        foreach($_POST['grades'] as $student_id => $grade_values) {
            // Prepare the statement
            $tp = $grade_values['tp'];
            $ds = $grade_values['ds'];
            $ex = $grade_values['ex'];
            $update_grades_request= "UPDATE grades SET tp=:tp, ds=:ds, ex=:ex WHERE student_id=:student_id AND course_id = $course_id";
            $update_grades_response= $conn->prepare($update_grades_request);
            // Bind & Execute
            $update_grades_response->execute(array(
                ':tp' => $tp,
                ':ds' => $ds,
                ':ex' => $ex,
                ':student_id' => $student_id
            ));
        }
        // Redirection
        echo '<script>alert("Grades Updated !"); window.location.href = "index.php";</script>';
    }
?>

<div class="container">
    <?php $course_id= $_GET['course_id']; ?>
    <form method="POST" action="grades.php?course_id=<?php echo $course_id; ?>">
        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                <th scope="col">ID</th>
                <th scope="col">First name</th>
                <th scope="col">Last name</th>
                <th scope="col">TP</th>
                <th scope="col">DS</th>
                <th scope="col">EX</th>
                </tr>
            </thead>
            <tbody>
            <?php
                
                require_once 'database/connect.php';
                $conn = connect();
                if(!$conn) die("Connection Failed !") ;
                //$course_id= $_GET['course_id'];
                $students_request = "SELECT * FROM students 
                    JOIN grades ON students.student_id = grades.student_id
                    WHERE grades.course_id = $course_id
                    GROUP BY students.student_id";

                $students_response= $conn->query($students_request);
                $students= $students_response->fetchAll(PDO::FETCH_ASSOC);
                foreach($students as $student){
                    $id = $student['student_id'];
                    $grades_request = "SELECT * FROM grades WHERE student_id = $id AND course_id = $course_id";
                    $grades_response= $conn->query($grades_request);
                    $grades= $grades_response->fetchAll(PDO::FETCH_ASSOC);
                    foreach($grades as $grade){
                    ?>
                        <tr>
                        <td> <?php echo $student['student_id']?> </td>
                        <td> <?php echo $student['first_name']?> </td>
                        <td> <?php echo $student['last_name']?> </td>
                        <td> <input id="grade-input" type="text" name="grades[<?php echo $student['student_id']?>][tp]" value="<?php echo $grade['tp']?>" required/> </td>
                        <td> <input id="grade-input" type="text" name="grades[<?php echo $student['student_id']?>][ds]" value="<?php echo $grade['ds']?>" required/> </td>
                        <td> <input id="grade-input" type="text" name="grades[<?php echo $student['student_id']?>][ex]" value="<?php echo $grade['ex']?>" required/> </td>
                        </tr>
                    <?php
                    }
                }
            ?>
            </tbody>
        </table>
        <div class="text-center">
            <button type="submit" name="submit" class="button">Save</button>
            <button type="reset" class="button">Reset</button>
            <button type="button" class="button" onclick="clearGrades()">Clear</button>
        </div>
    </form>
</div>
<?php include 'footer.php'; ?>