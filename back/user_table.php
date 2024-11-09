<?php

include "../include/connect.php";


if (isset($_POST['usead'])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $name = validate($_POST['name']);
    $username = validate($_POST['username']);
    $email = validate($_POST['email']);
    $contact = validate($_POST['contact']);
    $password = validate($_POST['password']);


    if (empty($name) || empty($username) || empty($email) || empty($contact) || empty($password)) {
        $errorMessage = "All fields are required";
        echo $errorMessage;
        exit;
    }

    $check_rows = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' OR email = '$email'");
    if (mysqli_num_rows($check_rows) > 0) {
        $error_message = 'Username or email already exists';
        echo $error_message;
        exit;
    } else {
        $sql = "INSERT INTO users (name, username, email, contact, password) VALUES ('$name', '$username', '$email', '$contact', '$password')";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "User added successfully";
            echo $successMessage;
        } else {
            $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
            echo $errorMessage;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/boostrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php include "backbar.php"; ?>

    <div class="container justify-content-center mt-3">
        <div class="mb-2">
            <button type="button" class="btn btn-primary mt-3 mb-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Create User
            </button>
            <h2>User List</h2>
            <hr>
        </div>
        <table class="table table-light table-striped table-hover table-bordered caption-top shadow-lg table-condensed">
            <thead style='text-align: center; vertical-align: middle;'>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Email</th>
                    <th scipe="col">Profile Image</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //read all row from database table
                $sql = "SELECT * FROM users";
                $result = $conn->query($sql);

                $count = 1;

                while ($row = $result->fetch_assoc()) {
                ?>

                    <tr>
                        <td style='text-align: center; vertical-align: middle;'> <?= $count++; ?> </td>
                        <td style='text-align: center; vertical-align: middle;'> <?= $row['name'] ?> </td>
                        <td style='text-align: center; vertical-align: middle;'> <?= $row['username'] ?> </td>|
                        <td style='text-align: center; vertical-align: middle;'> <?= $row['contact'] ?> </td>
                        <td style='text-align: center; vertical-align: middle;'> <?= $row['email'] ?> </td>
                        <td style='text-align: center; vertical-align: middle;'>
                            <img src="../front/uploads/<?= $row['profile_image'] ?>" alt="image" style="width: 100px; height: auto;">
                        </td>



                        <td style='text-align: center; vertical-align: middle;'>
                            <div class='btn-group btn-group '>
                                <a class='btn btn-danger ' href='genre_delete.php?deleteID=<?= $row['ID']; ?>' onClick="return confirm('Are you sure you want to Delete this Genre Entry?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php
                }

                $conn->close();
                ?>
            </tbody>


    </div>


    <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add an Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="user_table.php" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required value="" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required value="" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="contact" class="form-control" id="contact" name="contact" required value="" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required value="" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required value="" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="acctype" class="form-label">Role</label>
                            <select class="form-select" id="acctype" name="acctype" required value="" autocomplete="off">
                                <option selected disabled>Account Type</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="usead">Save User</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>

</html>