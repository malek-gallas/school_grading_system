<?php
    session_start();
    if(!isset($_SESSION['account'])){
        header('Location: login.php');
        exit();
    }
    elseif ($_SESSION['account'] == "teacher") {
        header('Location: teacher_index.php?teacher_id=' . urlencode($_SESSION['teacher_id']));
        exit();
    } 
    elseif ($_SESSION['account'] == "student") {
        header('Location: student_index.php?student_id=' . urlencode($_SESSION['student_id']));
        exit();
    }
    else{
        header('Location: error.php');
        exit();
    }
?>
