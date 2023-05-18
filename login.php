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
            <h1>Login</h1>
            <form action="login.php" method="post">
                <hr>
                <div class="accounttype">
                    <input type="radio" value="teacher" id="radioOne" name="account" checked/>
                    <label for="radioOne" class="radio" chec>Teacher</label>
                    <input type="radio" value="student" id="radioTwo" name="account" />
                    <label for="radioTwo" class="radio">Student</label>
                </div>
                <hr>
                <label id="icon" for="email"><i class="icon-envelope "></i></label>
                <input type="email" name="email" id="email" placeholder="Email" required/>
                <label id="icon" for="password"><i class="icon-shield"></i></label>
                <input type="password" name="password" id="password" placeholder="Password" required/>
                <button type="submit" class="button">Login</button>
                <p>Not a member ? Sign-up <a href="register.php">here</a>.</p>
            </form>
        </div>
        <?php
        if (isset($_POST["account"]) && isset($_POST["email"]) && isset($_POST["password"])) {
            $account = $_POST['account'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            require_once("database\connect.php");
            $conn = connect();
            if(!$conn) die("Connection Failed !") ;

            if ($account == "teacher") {
                $res = $conn->prepare("SELECT * FROM teachers 
                WHERE email = :email AND password = :password");
            } elseif ($account == "student") {
                $res = $conn->prepare("SELECT * FROM students 
                WHERE email = :email AND password = :password");
            }

            try {
                $res->execute([':email' => $email, ':password' => $password]);
                if ($res->rowCount() == 1) {
                    $user = $res->fetch(PDO::FETCH_ASSOC);
                    session_start();
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['account'] = $account;
                    if ($account == "teacher") {
                        $_SESSION['teacher_id'] = $user['teacher_id'];
                    } 
                    elseif ($account == "student") {
                        $_SESSION['student_id'] = $user['student_id'];
                    }
                    header('Location: index.php');
                    exit();
                }
                else {
                    echo '<script>alert("Wrong Email or Password !"); window.location.href = "login.php";</script>';
                } 
            } catch (PDOException $e) {
                echo "SQL Error : " . $e->getMessage();
            }
        }
        ?>
    </body>
</html>