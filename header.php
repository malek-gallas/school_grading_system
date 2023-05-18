<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPI-DS</title>
    <!-- BootStrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" 
    crossorigin="anonymous">
    <!-- FontAwsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public\css\style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-5">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="index.php">Home</a>
        <h1 class="navbar-heading text-center flex-grow-1 mx-3">
            <?php echo "Welcome " . $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?>
        </h1>
        <form method="post" class="form-inline">
            <button class="btn btn-secondary" type="submit" name="logout">Logout</button>
        </form>
    </div>
</nav>

<?php
    if(isset($_POST["logout"])){
        session_start();
        session_destroy();
        header('Location: login.php');
        exit();
    }
?>