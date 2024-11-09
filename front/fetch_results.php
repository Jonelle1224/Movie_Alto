<?php
include "../include/connect.php";

// Get the selected genres and years from the POST request
$genres = isset($_POST['genres']) ? json_decode($_POST['genres'], true) : [];
$years = isset($_POST['years']) ? json_decode($_POST['years'], true) : [];

// Build the SQL query based on selected filters
$sql = "SELECT * FROM movies WHERE 1=1";

// Add genre filter
if (!empty($genres)) {
    // Escape each genre for SQL injection protection
    $genreList = "'" . implode("', '", array_map('real_escape_string', $genres)) . "'";
    $sql .= " AND genre IN ($genreList)";
}

if (!empty($years)) {
    
    $yearList = "'" . implode("', '", array_map('real_escape_string', $years)) . "'";
    $sql .= " AND year IN ($yearList)";
}

// Execute the query
$result = $conn->query($sql);

// Prepare the results to return
if ($result) {
    if ($result->num_rows > 0) {
        // Start a Bootstrap card container
        echo '<div class="row">';
        
        // Loop through the results
        while ($row = $result->fetch_assoc()) {
            if (isset($row['ID'])) {
                $src = 'uploads/' . htmlspecialchars($row['image']);
                echo '<div class="col-md-2 mb-2">';
                echo '<div class="card" style="width: 13rem; height: 12rem; overflow: hidden;">';
                echo '<img src="' . $src . '" class="card-img-top" style="width: 100%; height: 7rem; object-fit: cover;" alt="' . htmlspecialchars($row['title']) . '">';
                echo '<div class="card-body p-1">';
                echo '<h5 class="card-title" style="font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' . htmlspecialchars($row['title']) . '</h5>';
                echo '<a href="movie_details.php?id=' . htmlspecialchars($row['ID']) . '" class="btn btn-info btn-sm">Details</a>';
                echo '<a href="add_watchlist.php?movie_id=' . htmlspecialchars($row['ID']) . '" class="btn btn-primary btn-sm">Add to Watch List</a>';
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
