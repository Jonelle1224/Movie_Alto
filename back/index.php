<?php

include "../include/connect.php";

if (!isset($_SESSION['user'])) {
    header("Location: admin_create.php");
    exit; // It's a good practice to call exit after a header redirect
} elseif ($_SESSION['user']['acctype'] != 'admin' && $_SESSION['user']['acctype'] != 'superadmin') {
    header("Location: admin_create.php");
    exit; // Also exit here to stop script execution
}
$user = $_SESSION['user'];



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../back/css/bootstrap.css">
    <link rel="stylesheet" href="../back/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>

<body>
    <?php
    include "backbar.php";
    ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Admin Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-2">
                                <div class="card ">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Total Users</h5>
                                        <i class="bi bi-person-circle text-warning" style="font-size: 30px;"></i>
                                        <?php

                                        $sql = "SELECT COUNT(*) AS user FROM users";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Fetch the row and display the count
                                            $row = $result->fetch_assoc();
                                            echo "<h1>" . $row['user'] . "</h1>";
                                        }
                                        ?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="user_table.php" class="btn btn-primary">View</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-center">Total Movies</h5>
                                        <i class="bi bi-film text-warning" style="font-size: 30px;"></i>
                                        <?php

                                        $sql = "SELECT COUNT(*) AS mlist FROM movies";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Fetch the row and display the count
                                            $row = $result->fetch_assoc();
                                            echo "<h1>" . $row['mlist'] . "</h1>";
                                        }
                                        ?>
                                    </div>
                                    <div class="card-footer text-center ">
                                        <a href="movie_create.php" class="btn btn-primary">View</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card ">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Genres</h5>
                                        <i class="bi bi-list-stars text-warning" style="font-size: 30px;"></i>
                                        <?php

                                        $sql = "SELECT COUNT(*) AS genres FROM genres";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Fetch the row and display the count
                                            $row = $result->fetch_assoc();
                                            echo "<h1>" . $row['genres'] . "</h1>";
                                        }
                                        ?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="genre_add.php" class="btn btn-primary">View</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card ">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Year</h5>
                                        <i class="bi bi-calendar-fill text-warning" style="font-size: 30px;"></i>
                                        <?php

                                        $sql = "SELECT COUNT(*) AS years FROM years";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Fetch the row and display the count
                                            $row = $result->fetch_assoc();
                                            echo "<h1>" . $row['years'] . "</h1>";
                                        }
                                        ?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="year.php" class="btn btn-primary">View</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card ">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Rating</h5>
                                        <i class="bi bi-star-fill text-warning" style="font-size: 30px;"></i>
                                        <?php

                                        $sql = "SELECT COUNT(*) AS ratings FROM review_rate";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Fetch the row and display the count
                                            $row = $result->fetch_assoc();
                                            echo "<h1>" . $row['ratings'] . "</h1>";
                                        }
                                        ?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="ratings.php" class="btn btn-primary">View</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card <?php echo ($_SESSION['user']['acctype'] == 'admin') ? 'bg-secondary text-bold' : ''; ?>">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Admin</h5>
                                        <i class="bi bi-shield-shaded text-warning" style="font-size: 30px;"></i>
                                        <?php
                                        $sql = "SELECT COUNT(*) AS admins FROM admins";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Fetch the row and display the count
                                            $row = $result->fetch_assoc();
                                            echo "<h1>" . $row['admins'] . "</h1>";
                                        }
                                        ?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <?php if ($_SESSION['user']['acctype'] == 'admin') : ?>
                                            <a href="#" class="btn btn-danger" onclick="showAlert()">
                                                View
                                            </a>
                                        <?php else : ?>
                                            <a href="admin_add.php" class="btn btn-primary">
                                                View
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Sentiment Scores</h4>
                    </div>
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-2">
                                <div class="card ">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Sentiment Scores and Label</h5>
                                        <i class="bi bi-list-ol text-warning" style="font-size: 30px;"></i>
                                        <?php

                                        $sql = "SELECT COUNT(*) AS scores FROM review_rate";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            // Fetch the row and display the count  
                                            $row = $result->fetch_assoc();
                                            echo "<h1>" . $row['scores'] . "</h1>";
                                        }
                                        ?>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="senti_scores.php" class="btn btn-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</body>
<script>
    function showAlert() {
        Swal.fire({
            title: 'Access Denied',
            text: 'You do not have the privilege to enter this section.',
            icon: 'warning',
            confirmButtonText: 'Okay'
        }).then((result) => {
            // Redirect if the user clicks 'Okay'
            if (result.isConfirmed) {
                window.location.href = 'index.php'; // Change this to the URL you want to redirect to
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>