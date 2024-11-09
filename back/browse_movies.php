<?php
include "../include/connect.php";
if (!isset($_SESSION['user'])) {
    header("Location: back/user/user_data.php");
}
$user = $_SESSION['user'];

$query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';

// Get the selected genres and years
$genres = isset($_GET['genres']) ? $_GET['genres'] : [];
$years = isset($_GET['years']) ? $_GET['years'] : [];

// Build the SQL query based on selected filters
$sql = "SELECT * FROM movies WHERE 1=1";

// Add search condition
if (!empty($query)) {
    $sql .= " AND (title LIKE '%$query%' OR description LIKE '%$query%' OR genre LIKE '%$query%' OR director LIKE '%$query%' OR year LIKE '%$query%')";
}

// Add genre filter
if (!empty($genres)) {
    $genreList = "'" . implode("', '", array_map('real_escape_string', $genres)) . "'";
    $sql .= " AND genre IN ($genreList)";
}

// Add year filter
if (!empty($years)) {
    $yearList = "'" . implode("', '", array_map('real_escape_string', $years)) . "'";
    $sql .= " AND year IN ($yearList)";
}

// Execute the query
$result = $conn->query($sql);

// Prepare response
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Alto</title>
    <link rel="stylesheet" href="front/css/bootstrap.css">
    <link rel="stylesheet" href="front/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php include "frontbar.php" ?>
    <div class="container align-center mt-3 shadow-sm mb-2">
        <form class="d-flex ml-2" role="search" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            <input class="form-control me-2" type="search" name="query" placeholder="Search (Title, Genre, Year)" aria-label="Search" autocomplete
                = "off">
            <button class="btn btn-success" type="submit">Search</button>
        </form>
    </div>

    <?php if (isset($_SESSION['watchlist_message'])): ?>
        <div class="alert alert-success text-center">
            <?php echo $_SESSION['watchlist_message']; ?>
        </div>
        <?php unset($_SESSION['watchlist_message']); // Clear the message after displaying ?>
    <?php endif; ?>
    <div class="container mt-3">
    <?php
    if ($result) {
        if ($result->num_rows > 0) {
            // Start a Bootstrap card container
            echo '<div class="row">';

            // Loop through the results
            while ($row = $result->fetch_assoc()) {

                if (isset($row['ID'])) {
                    $src = 'uploads/' . htmlspecialchars($row['image']);
                    echo '<div class="col-md-2 mb-2">';
                    echo '<div class="card" style="width: 13rem; height: 23rem; overflow: hidden;">';
                    echo '<img src="' . $src . '" class="card-img-top" style="width: 100%; height: 18rem; object-fit: cover;" alt="' . htmlspecialchars($row['title']) . '">';
                    echo '<div class="card-body p-1">';
                    echo '<h5 class="card-title" style="font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' . htmlspecialchars($row['title']) . '</h5>';
                    echo '<a href="movie_details.php?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-info btn-sm ">Details</a>';
                    echo '<a href="add_watchlist.php?movie_id=' . htmlspecialchars($row['ID']) . '" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle-fill"></i></a>';
                    echo '</div>'; // Close card body
                    echo '</div>'; // Close card
                    echo '</div>'; // Close column
                } else {
                    echo '<div class="col-md-3 col-sm-6 mb-4"><div class="card" style="width: 8rem; height: 8rem;"><div class="card-body">Error: Movie ID is missing.</div></div></div>';
                }
            }

            echo '</div>'; // Close the row
        } else {
            echo '<p>No results found.</p>';
        }
    } else {
        echo '<p>Error executing query: ' . htmlspecialchars($conn->error) . '</p>';
    }
    ?>
</div>


</body>
<script src="scripts/genre_filter.js"></script>
</html>