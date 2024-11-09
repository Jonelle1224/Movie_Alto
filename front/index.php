<?php

include "../include/connect.php";

if (isset($_POST['LogIn'])) {


    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    // $username= $_POST['username'];

    $sql = "SELECT * FROM users WHERE (email = '$email' OR username = '$email') AND password = '$password'";
    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        $data = $query->fetch_assoc();
        if (($email == $data['email']) || ($email == $data['username'])  && ($password == $data['password'])) {

            $_SESSION['user'] = $data;
            $_SESSION['user_id'] = $data['ID']; // ID from users table
            $_SESSION['username'] = $data['username'];

            if ($_SESSION['user']['role'] == "user") {
                header("Location: ../MovieAlto.php");
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
    <link rel="stylesheet" href="../front/css/bootstrap.min.css">
    <link rel="stylesheet" href="../front/css/bootstrap.css">

</head>

<body>
    <div class="container p-5 mt-5">
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
                                    <label for="email" class="form-label">Email or Username</label>
                                    <input type="text" name="email" class="form-control" id="email" autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>
                                <div class="mb-3">
                                    <input type="submit" class="btn btn-warning btn-lg btn-block" name="LogIn" class="btn btn-primary" value="Login">
                                    <a class="btn btn-primary btn-lg btn-block " type="submit" name="Register" value="Regis" href="user_reg.php">Register </a>
                                    <a class="btn btn-success btn-lg btn-block float-end" type="submit" name="Adlog" value="Adlog" href="admin_create.php">Admin Log In </a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>