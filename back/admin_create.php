<?php

include "../include/connect.php";
$errorMessage ='Invalid credentials. Please try again.';

if (isset($_POST['adsub'])) {

    // Sanitize user input
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Sanitize both username and password
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    // SQL query to check username or email for both superadmin and admin
    $sql = "
        SELECT * FROM superadmin WHERE (username = '$username' OR email = '$username') AND password = '$password'
        UNION
        SELECT * FROM admins WHERE (username = '$username' OR email = '$username') AND password = '$password'
    ";
    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        // Fetch the user data
        $data = $query->fetch_assoc();

        // Only store necessary information
        $_SESSION['user'] = [
            'username' => $data['username'],
            'acctype' => $data['acctype'],
            // Do not include the password
        ];

        // Redirect based on user type
        if ($_SESSION['user']['acctype'] == "superadmin") {
            header("Location: index.php");
            exit();
        } elseif ($_SESSION['user']['acctype'] == "admin") {
            header("Location: index.php"); // Change to the appropriate page for admin
            exit();
        }
    } else {
        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
        <strong>Warning!</strong> {$errorMessage}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/boostrap.min.css">
</head>

<body>
    

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">
                            Admin Portal
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Admin Username</label>
                                <input type="text" name="username" class="form-control" id="username">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                            <button class="btn btn-primary btn-lg btn-block" type="submit" name="adsub">Login</button>
                            <a class="btn btn-warning btn-lg btn-block float-end" href="user_data.php">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>