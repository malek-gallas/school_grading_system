<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>EPI-DS</title>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600' rel='stylesheet' type='text/css'>
        <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css" rel="stylesheet">
        <link rel="stylesheet" href="public\css\style.css">
    </head>
    <body>
        <div class="testbox">
            <h1>Registration</h1>
            <form action="register.php" method="post">
                <hr>
                <div class="accounttype">
                    <input type="radio" value="teacher" id="radioOne" name="account" checked/>
                    <label for="radioOne" class="radio" chec>Teacher</label>
                    <input type="radio" value="student" id="radioTwo" name="account" />
                    <label for="radioTwo" class="radio">Student</label>
                </div>
                <hr>
                <label id="icon" for="first_name"><i class="icon-user"></i></label>
                <input type="text" name="first_name" id="first_name" placeholder="First Name" required/>
                <label id="icon" for="last_name"><i class="icon-user"></i></label>
                <input type="text" name="last_name" id="last_name" placeholder="Last Name" required/>
                <label id="icon" for="email"><i class="icon-envelope "></i></label>
                <input type="email" name="email" id="email" placeholder="Email" required/>
                <label id="icon" for="password"><i class="icon-shield"></i></label>
                <input type="password" name="password" id="password" placeholder="Password" required/>
                <button type="submit" class="button">Register</button>
                <p>Already a member ? Sign-in <a href="login.php">here</a>.</p>
            </form>
        </div>
        <?php
        if (isset($_POST["account"]) && isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["password"])) {
            $account = $_POST['account'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            require_once("database\connect.php");
            $conn = connect();
            if(!$conn) die("Connection Failed !") ;

            // Check if email already exists in either students or teachers table
            $emailExistsQuery = "SELECT email FROM students WHERE email = :email 
            UNION SELECT email FROM teachers WHERE email = :email";
            $emailExistsStmt = $conn->prepare($emailExistsQuery);
            $emailExistsStmt->bindParam(':email', $email);
            $emailExistsStmt->execute();
            $emailExists = $emailExistsStmt->fetch();

            if ($emailExists) echo '<script>alert("User already exists !");</script>';
            else {
                try {
                    if ($account == "teacher") {
                        $req = "INSERT INTO teachers (first_name, last_name, email, password) 
                        VALUES(:first_name, :last_name, :email, :password)";
                    } elseif ($account == "student") {
                        $req = "INSERT INTO students (first_name, last_name, email, password) 
                        VALUES(:first_name, :last_name, :email, :password)";
                    }
                    
                    $stmt = $conn->prepare($req);
                    $stmt->bindParam(':first_name', $first_name);
                    $stmt->bindParam(':last_name', $last_name);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);
                    $res = $stmt->execute();
                    
                    if ($res > 0) {
                        echo '<script>alert("User added"); window.location.href = "login.php";</script>';
                        exit();
                    } else {
                        header('Location: error.php');
                        exit();
                    }
                } catch (PDOException $e) {
                    echo "SQL ERROR: " . $e->getMessage();
                }
            }
        }
        ?>
    </body>
</html>