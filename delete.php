<?php
// Connect to database
require_once 'database/connect.php';
$conn = connect();
if (!$conn) die("Connection Failed!");

if (isset($_GET['course_id'])) {
    // Get the URL data
    $course_id = $_GET['course_id'];
    // Prepare the DELETE statement
    $sql = "DELETE FROM courses WHERE course_id = :course_id";
    $stmt = $conn->prepare($sql);
    // Bind Parameters
    $stmt->bindParam(":course_id", $course_id);
    // Execute the DELETE statement
    if ($stmt->execute()) {
        // Reset the auto-increment value
        $sql = "ALTER TABLE courses AUTO_INCREMENT = 1";
        $conn->query($sql);
        // Redirection
        header("Location: index.php");
        exit();
    } else {
        // Redirection
        header("Location: error.php");
        exit();
    }
} else {
    // Redirection
    header("Location: index.php");
    exit();
}
?>