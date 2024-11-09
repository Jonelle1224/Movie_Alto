<?php

include "../../includes/connect.php";

if (isset($_POST['LogIn'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];
    // $username= $_POST['username'];

    $sql = "SELECT * FROM users WHERE email = '$email' OR username = '$username' AND password = '$password'";
    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        $data = $query->fetch_assoc();
        if (($email == $data['email']) || ($username == $data['username'])  && ($password == $data['password'])) {

            $_SESSION['user'] = $data;

            if ($_SESSION['user']['role'] == "admin") {
                header("Location: ../index.php");
                exit();
            } else if ($_SESSION['user']['role'] == "user") {
                header("Location: ../../MovieAlto.php");
                exit();
            } else {
                echo "<span style='color:red'>Invalid Username or Password </span>";
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal</title>
    <link rel="stylesheet" href="../../front/css/bootstrap.css">
    <link rel="stylesheet" href="../../front/css/bootstrap.min.css">

</head>

<body>
    <div class="container p-5mt-5">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                Log In
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" name="email" class="form-control" id="email" autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>
                                <div class="mb-3">
                                    <input type="submit" class="btn btn-warning btn-lg btn-block" name="LogIn" class="btn btn-primary" value="Login">
                                    <a class="btn btn-primary btn-lg btn-block " type="submit" name="LogIn" value="LogIn" href="../user/user_reg.php">Register </a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>