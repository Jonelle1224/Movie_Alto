<?php


require '../../includes/connect.php';


// if ($_SERVER['REQUEST_METHOD'] == 'POST') {

//     $name = mysqli_real_escape_string($connect, $_POST['name']);
//     $username = mysqli_real_escape_string($connect, $_POST['username']);
//     $email = mysqli_real_escape_string($connect, $_POST['email']);
//     $password = mysqli_real_escape_string($connect, $_POST['password']);
//     $confirm_password = mysqli_real_escape_string($connect, $_POST['confirm_password']);
//     $contact = mysqli_real_escape_string($connect, $_POST['contact']);
//     $hashed_password = password_hash($password, PASSWORD_DEFAULT);


//     $check_email = "SELECT * FROM users WHERE email = '$email'";
//     $result = mysqli_query($connect, $check_email);

//     if (mysqli_num_rows($result) > 0) {
//         echo "
//             <div class='alert alert-dismissible alert-warning'>
//                 <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
//                     <h4 class='alert-heading'>Warning!</h4>
//                         <p class='mb-0'>Email Already Exists</p>
//             </div>        
//                     ";
//     } else { 
//         if ($password === $confirm_password) {
//         $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
//         if (mysqli_query($connect, $sql)) {
//             $_SESSION['username'] = $username;
//             header("Location: navbar/backbar.php");
//         } else {
//             echo "<div class='alert alert-dismissible alert-warning'>
//                 <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
//                     <h4 class='alert-heading'>Warning!</h4>
//                     <p class='mb-0'>Password Doesnt't match</p>";
//         }
//     }
// }

// }


if (isset($_POST['Regsubmit'])) {

    // Sanitize and validate input
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $contact = $_POST['contact'];



    // Check if email already exists
    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE Email = '$email'");

    if (mysqli_num_rows($check_email) > 0) {
        echo "
            <div class='alert alert-dismissible alert-warning'>
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                <p class='mb-0'>Email Already Exists</p>
            </div>      
        ";
    } else {

        if ($password === $confirm_password) {
            $sql = "INSERT INTO users (Name, Username, Email, Password, Contact) VALUES ('$name', '$username', '$email', '$password', '$contact')";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['username'] = $username;
                header("Location: ../user/user_data.php");
                exit(); // Make sure to exit after redirection
            } else {
                echo "<div class='alert alert-dismissible alert-warning'>
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        <p class='mb-0'>Error during registration: " . mysqli_error($conn) . "</p>
                      </div>";
            }
        } else {
            echo "<div class='alert alert-dismissible alert-warning'>
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                    <p class='mb-0'>Passwords do not match</p>
                  </div>";
        }
    }
}






?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../../front/css/bootstrap.css">
    <link rel="stylesheet" href="../../front/css/bootstrap.min.css">
</head>
<!-- 
<body>
    <div class="container border border-primary">
        <div class="modal fade" id="regModal" tabindex="-1" aria-labelledby="regModalLabel" aria-hidden="true" data-bs-target="#regModal" data-bs-toggle="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="regModalLabel">Register</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="user_reg.php" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required value="">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required value="">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required value="">
                                </div>
                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="text" class="form-control" id="contact" name="contact" required value="">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required value="">
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required value="">

                                    <hr>
                                    <button type="submit" class="btn btn-primary" name="Regsubmit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <div class="container p-5">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">
                    Register
                </h3>
            </div>
            <div class="card-body">
                <form action="user_reg.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required value="">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required value="">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required value="">
                    </div>
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="text" class="form-control" id="contact" name="contact" required value="">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required value="">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required value="">

                        <hr>
                        <button type="submit" class="btn btn-primary" name="Regsubmit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>