<?php
    session_start();
    if(($_SESSION['account']) != 'teacher'){
        header('Location: login.php');
        exit();
    }
    $teacher_id=$_GET['teacher_id'];
?> 
<?php include 'header.php'; ?> 
<div class="container">
    <a href="create.php?teacher_id=<?php echo $teacher_id ;?>" class="btn btn-dark mb-3">Add Course</a>
    <table class="table table-hover text-center">
        <thead class="table-dark">
            <tr>
            <th scope="col">Courses</th>
            <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            
            require_once 'database\connect.php';
            $conn = connect();
            if(!$conn) die("Connection Failed !") ;
            $stmt= $conn->prepare("SELECT * FROM courses where teacher_id= :teacher_id");
            $stmt->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT);
            $stmt->execute();
            $courses= $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($courses as $course){
            ?>
            <tr onclick="location.href='grades.php?course_id=<?php echo $course['course_id']?>';">
            <td> <?php echo $course['course_name']?> </td>
            <td>
                <a href="update.php?course_id=<?php echo $course['course_id']?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                <a href="delete.php?course_id=<?php echo $course['course_id']?>" class="link-dark"><i class="fa-solid fa-trash fs-5 me-3"></i></a>
            </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>