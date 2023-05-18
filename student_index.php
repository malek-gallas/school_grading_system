<?php
    session_start();
    if(($_SESSION['account']) != 'student'){
        header('Location: login.php');
        exit();
    }
?>
<?php include 'header.php'; ?>  
<div class="container">
    <form method="POST">
        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Course</th>
                    <th scope="col">TP</th>
                    <th scope="col">DS</th>
                    <th scope="col">EX</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $student_id= $_GET['student_id'];
                require_once 'database/connect.php';
                $conn = connect();
                if(!$conn) die("Connection Failed !");
                $grades_request= "SELECT * FROM grades WHERE student_id = $student_id";
                $grades_response= $conn->query($grades_request);
                $grades= $grades_response->fetchAll(PDO::FETCH_ASSOC);
                foreach($grades as $grade){
                    $course_id = $grade['course_id'];
                    $courses_request= "SELECT * FROM courses WHERE course_id = $course_id";
                    $courses_response= $conn->query($courses_request);
                    $courses= $courses_response->fetchAll(PDO::FETCH_ASSOC);
                    foreach($courses as $course){
                    ?>
                        <tr>
                            <td> <?php echo $course['course_name']?> </td>
                            <td> <span><?php echo $grade['tp']?></span> </td>
                            <td> <span><?php echo $grade['ds']?></span> </td>
                            <td> <span><?php echo $grade['ex']?></span> </td>
                        </tr>
                    <?php
                    }
                }
            ?>
            </tbody>
        </table>
    </form>
</div>
<?php include 'footer.php'; ?>