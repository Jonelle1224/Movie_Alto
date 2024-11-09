<?php
session_start();
include "../include/connect.php";

$error = ''; // Initialize error message variable

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

    $sql = "SELECT * FROM users WHERE (email = '$email' OR username = '$email') AND password = '$password'";
    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        $data = $query->fetch_assoc();
        if (($email == $data['email']) || ($email == $data['username']) && ($password == $data['password'])) {
            $_SESSION['user'] = $data;
            $_SESSION['user_id'] = $data['ID'];
            $_SESSION['username'] = $data['username'];

            if ($_SESSION['user']['role'] == "user") {
                header("Location: ../MovieAlto.php");
                exit();
            }
        }
    } else {
        $error = 'Invalid Username or Password';
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
    <style>
        .forgot-password {
            color: #007bff; /* Blue color for Forgot Password link */
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .password-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container p-5 mt-2">
        <div class="container mt-2">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">Log In</h3>
                        </div>
                        <div class="card-body">
                            <!-- Display alert if there's an error -->
                            <?php if ($error): ?>
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <?php echo $error; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <div class="form-floating mb-3">
                                    <input type="text" name="email" id= "floatingInput" placeholder="name@example.com" class="form-control" id="email" autocomplete="off">
                                    <label for="floatingInput" class="form-label">Email or Username</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" name="password" id="password" placeholder="password" class="form-control" id="password">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="password-options mt-2">
                                        <!-- Show Password Checkbox -->
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">
                                            <label class="form-check-label" for="showPassword">Show Password</label>
                                        </div>
                                        <!-- Forgot Password Link -->
                                        <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <input type="submit" class="btn btn-warning btn-lg btn-block" name="LogIn" value="Login">
                                    <a class="btn btn-primary btn-lg btn-block" href="user_reg.php">Register</a>
                                    <a class="btn btn-success btn-lg btn-block float-end" href="admin_create.php">Admin Log In</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for toggling password visibility -->
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>