<?php

include "../include/connect.php";

$year = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $year = $_POST['year'];

    $checkrows = mysqli_query($conn, "SELECT * FROM years WHERE year = '$year'");

    do {

        if (empty($year)) {
            $errorMessage = "Add a year";
            break;
        }

        if ($checkrows->num_rows > 0) {
            $errorMessage = "Year already exists";
            break;
        } else {
            $sql = "INSERT IGNORE INTO years (year)
                VALUES ('$year')";
        }

        $query = $conn->query($sql);

        if ($query) {
            $successMessage = "Year added successfully";

            $year = "";

            header("Location: " . $_SERVER['PHP_SELF']);
        } else {
            $errorMessage = "Database error: " . $conn->error;
        }
    } while (false);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Year Add</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/boostrap.min.css">
</head>

<body>
    <?php
    include "backbar.php";
    ?>

    <div class="container justify-content-center mt-3">

        <?php if (!empty($errorMessage)) { ?>
            <div class="alert alert-dismissible alert-danger" role="alert"><?= $errorMessage ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>
        <?php if (!empty($successMessage)) { ?>
            <div class="alert alert-success" role="alert"><?= $successMessage ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="mb-2">

            <button type="button" class="btn btn-primary mt-3 mb-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Add Year
            </button>

            <h2>Year List</h2>
                <div class="container justify-content-center mt-3">
                    <table class="table table-light table-striped table-hover table-bordered caption-top shadow-lg table-condensed">
                        <thead style='text-align: center; vertical-align: middle;'>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Year</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //read all row from database table
                            $sql = "SELECT * FROM years";
                            $result = $conn->query($sql);

                            $count = 1;

                            while ($row = $result->fetch_assoc()) {
                            ?>

                                <tr>
                                    <td style='text-align: center; vertical-align: middle;'> <?= $count++; ?> </td>
                                    <td style='text-align: center; vertical-align: middle;'> <?= $row['year'] ?> </td>


                                    <td style='text-align: center; vertical-align: middle;'>
                                        <div class='btn-group btn-group '>
                                            <a class='btn btn-danger ' href='genre_delete.php?deleteID=<?= $row['ID']; ?>' onClick="return confirm('Are you sure you want to Delete this Year Entry?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }

                            $conn->close();
                            ?>
                        </tbody>


                </div>

                <div class="container justify-content-center mb-2">
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Add a Movie Year</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="year" class="form-label">Movie Year</label>
                                            <input type="text" class="form-control" id="year" name="year" value="<?= $year ?>" autocomplete="off" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
</html>