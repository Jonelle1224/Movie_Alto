<?php

include "../include/connect.php";


$genre = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $genre = $_POST['genre'];

    $checkrows = mysqli_query($conn, "SELECT * FROM genres WHERE genre_title = '$genre'");

    do {

        if (empty($genre)) {
            $errorMessage = "Add a genre";
            break;
        }

        if ($checkrows->num_rows > 0) {
            $errorMessage = "Genre already exists";
            break;
        } else {
            $sql = "INSERT IGNORE INTO genres (genre_title)
                VALUES ('$genre')";
        }


        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $conn->error;
            break;
        }

        $genre = "";

        $successMessage = "Movie added successfully";

        header("location: index.php");

        exit;
    } while (false);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/boostrap.min.css">
</head>

<body>
    <?php
    include "backbar.php";
    ?>

    <div class="container justify-content-center mt-3">
        <table class="table table-light table-striped table-hover table-bordered caption-top shadow-lg table-condensed">
            <thead style='text-align: center; vertical-align: middle;'>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Genre</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //read all row from database table
                $sql = "SELECT * FROM genres";
                $result = $conn->query($sql);

                $count = 1;

                while ($row = $result->fetch_assoc()) {
                ?>

                <tr>
                    <td style='text-align: center; vertical-align: middle;'> <?= $count++; ?> </td>
                    <td style='text-align: center; vertical-align: middle;'> <?= $row['genre_title'] ?> </td>

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
    <div class="container justify-content-center">

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



<div class="mb-1">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Add Genre
    </button>

    <h2>Genre List</h2>
    <hr>
</div>

<div class="container">
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Genre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Genre</label>
                            <input type="text" class="form-control" id="genre" name="genre" value="<?= $genre ?>" autocomplete="off" required>
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
</div>
</div>

</body>

</html>