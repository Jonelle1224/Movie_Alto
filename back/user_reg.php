<?php


include "../include/connect.php";


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


if (isset($_POST['Regsubmit']) == 'POST') {

    // Sanitize and validate input
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $contact = $_POST['contact'];



    // Check if email already exists
    $check_email = mysqli_query($conn, "SELECT * FROM users WHERE Email = '$email'");

    var_dump($name, $username, $email, $contact, $password, $confirm_password);

    if (mysqli_num_rows($check_email) > 0) {
        echo "
            <div class='alert alert-dismissible alert-warning'>
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                <p class='mb-0'>Email Already Exists</p>
            </div>      
        ";
    } else {

        if ($password === $confirm_password) {

            $sql = "INSERT INTO users (name, username, email, password, contact) VALUES ('$name', '$username', '$email', '$password', '$contact')";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['username'] = $username;
                header("Location: ../back/user_data.php");
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
    <link rel="stylesheet" href="../front/css/bootstrap.css">
    <link rel="stylesheet" href="../front/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-3 justify-content-center d-flex">
        <div class="card p-2 col-3 col-sm-4 col-md-8 col-lg-6 shadow-lg">
            <div class="card-header">
                <h3 class="text-center">
                    Register
                </h3>
            </div>
            <div class="card-body">
                <form action="user_reg.php" method="POST">
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="name" placeholder="Name" name="name" required value="" autocomplete="off">
                        <label for="name" class="form-label">Name</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="username" placeholder="Username" name="username" required value="" autocomplete="off">
                        <label for="username" class="form-label">Username</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" required value="" autocomplete="off">
                        <label for="email" class="form-label">Email</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="contact" placeholder="Contact" name="contact" required value="" autocomplete="off">
                        <label for="contact" class="form-label">Contact</label>

                    </div>
                    <div class="form-floating mb-1">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password" required value="" autocomplete="off">
                        <label for="password" class="form-label">Password</label>
                    </div>
                    <div class="form-floating mb-1 align-items-center">
                        
                        <input type="password" class="form-control" placeholder="Confirm Password" id="confirm_password" name="confirm_password" required value="" autocomplete="off">
                        <label for="confirm_password"  class="form-label">Confirm Password</label>

                        <hr>
                        <button type="submit" class="btn btn-warning" name="Regsubmit">Submit</button>
                        <a class="btn btn-primary float-end" href="user_data.php">Back</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>