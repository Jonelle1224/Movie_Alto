<?php
// Include database connection once
include "../include/connect.php";

// Function to get movies with positive reviews
function getPositiveReviews($conn)
{
    $query = "SELECT DISTINCT m.*, 
                     COUNT(r.movie_id) AS review_count,
                     AVG(r.ratings) AS average_rating
              FROM movies m
              LEFT JOIN review_rate r ON m.ID = r.movie_id
              WHERE r.sentiment_label = 'positive'
              GROUP BY m.ID
              ORDER BY review_count DESC, average_rating DESC";
    return $conn->query($query);
}

// Function to get movies based on highest user ratings
function getTopRatedMovies($conn)
{
    $query = "SELECT DISTINCT m.*, 
                     COUNT(r.movie_id) AS review_count,
                     AVG(r.ratings) AS average_rating
              FROM movies m
              LEFT JOIN review_rate r ON m.ID = r.movie_id
              GROUP BY m.ID
              ORDER BY average_rating DESC, review_count DESC";
    return $conn->query($query);
}

// Function to get movies based on user's watchlist genres
function getMoviesByWatchlistGenres($user_id, $conn)
{

    $watchlist_genres_query = "SELECT DISTINCT genre FROM movies 
                               INNER JOIN watchlist w ON movies.ID = w.movie_id
                               WHERE w.user_id = $user_id";
    $watchlist_genres_result = $conn->query($watchlist_genres_query);


    $watchlist_genres = [];


    if ($watchlist_genres_result && $watchlist_genres_result->num_rows > 0) {
        while ($genre = $watchlist_genres_result->fetch_assoc()) {
            $watchlist_genres[] = $genre['genre'];
        }
    } else {
        #echo "<p>No genres found in your watchlist.</p>";
        return null;
    }

    $genre_conditions = [];
    foreach ($watchlist_genres as $genre) {

        $genre = trim($genre);
        $genre_conditions[] = "m.genre LIKE '%" . $conn->real_escape_string($genre) . "%'";
    }


    $genre_condition_string = implode(" OR ", $genre_conditions);


    $query = "SELECT DISTINCT m.* 
              FROM movies m
              LEFT JOIN watchlist w ON m.ID = w.movie_id AND w.user_id = $user_id
              WHERE ($genre_condition_string) 
              AND w.movie_id IS NULL";


    $recommended_movies = $conn->query($query);

    // Check for errors
    if (!$recommended_movies) {
        echo "<p>Error fetching recommended movies: " . $conn->error . "</p>";
        return null;
    }

    return $recommended_movies;
}

function getMostReviewedMovies($conn)
{
    $query = "SELECT DISTINCT m.*, 
                     COUNT(r.movie_id) AS review_count,
                     AVG(r.ratings) AS average_rating
              FROM movies m
              LEFT JOIN review_rate r ON m.ID = r.movie_id
              GROUP BY m.ID
              ORDER BY review_count DESC";
    return $conn->query($query);
}

// Fetch the user_id from session
$user_id = $_SESSION['user_id'];


$positive_reviews = getPositiveReviews($conn);
$top_rated_movies = getTopRatedMovies($conn);
$movies_by_genre = getMoviesByWatchlistGenres($user_id, $conn);
$most_reviewed_movies = getMostReviewedMovies($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Recommendations</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php include "frontbar.php"; ?>
    <div class="container mt-2">

        <h1 class="text-center">Your Movie Recommendations, <?php echo htmlspecialchars($user['username']); ?>! <i class="bi bi-star-fill"></i></h1>
        <hr>

        <?php if (isset($_SESSION['watchlist_message'])): ?>
            <div class="alert alert-success text-center">
                <?php echo $_SESSION['watchlist_message']; ?>
            </div>
            <?php unset($_SESSION['watchlist_message']); // Clear the message after displaying 
            ?>
        <?php endif; ?>
        <?php
        // Reusable function to display a movie card
        function displayMovieCard($movie)
        {
            // Start the card container
            echo "<div class='col-md-3 mb-4'>";
            echo "<div class='card h-100 w-55'>";

            // Image with specific dimensions
            echo "<div class='mt-2' style='display: flex; justify-content: center; align-items: center; height: 240px;'>
                    <img src='../front/uploads/{$movie['image']}' style='width: 55%; height: 100%; object-fit: cover;' alt='{$movie['title']}'>
                  </div>";

            // Card body with content (Genres, Ratings, Reviews)
            echo "<div class='card-body'>";

            // Genre list with badges (no truncation needed)
            $genres = explode(',', $movie['genre']); // Assuming genres are stored in a comma-separated string
            $badgeHTML = '';
            foreach ($genres as $genre) {
                $badgeHTML .= "<span class='badge bg-danger me-1' title='{$genre}'>{$genre}</span>";
            }

            // Display genres (no truncation)
            echo "<p class='card-text'>Genres: {$badgeHTML}</p>";

            // Rating and review count with badges
            $average_rating = isset($movie['average_rating']) ? number_format($movie['average_rating'], 1) : 'N/A';
            $review_count = isset($movie['review_count']) ? $movie['review_count'] : '0';

            echo "<p class='card-text'>Average Rating: <span class='badge bg-info'>{$average_rating}</span></p>";
            echo "<p class='card-text'>Reviews: <span class='badge bg-info'>{$review_count}</span></p>";

            // Action buttons (Details and Add to Watchlist)
            echo '<a href="movie_details.php?id=' . htmlspecialchars($movie['ID']) . '" class="btn btn-outline-danger btn-sm">Details</a>';
            echo '<a href="add_watchlist.php?movie_id=' . htmlspecialchars($movie['ID']) . '" class="btn btn-outline-danger btn-sm"><i class="bi bi-plus-circle-fill"></i></a>';

            // Close the card body
            echo "</div>"; // card-body

            $titleWithBreak = substr($movie['title'], 0, 35) . "<br>" . substr($movie['title'], 35);
            echo "<div class='card-footer bg bg-secondary text-center'>
                    <span class='badge bg-dark' title='{$movie['title']}'>$titleWithBreak</span>
                  </div>";
            // Close the card container
            echo "</div>"; // card
            echo "</div>"; // col-md-3
        }




        ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#positive-reviews">Movies with Positive Reviews</a></li>
                <li class="breadcrumb-item"><a href="#top-rated">Top Rated Movies</a></li>
                <li class="breadcrumb-item"><a href="#most-reviewed">Most Reviewed Movies</a></li>
                <li class="breadcrumb-item"><a href="#watchlist-genres">Movies by Genre</a></li>
            </ol>
        </nav>
        <!-- Movies with Positive Reviews -->
        <div class="card bg-secondary mb-3 mt-2" id="positive-reviews">
            <div class="card-header bg-primary text-center">
                <h5 class="card-title"><i class="bi bi-star-fill"></i> Movies with Positive Reviews</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    if ($positive_reviews->num_rows > 0) {
                        while ($movie = $positive_reviews->fetch_assoc()) {
                            if ($movie['review_count'] > 0 && $movie['average_rating'] > 0) {
                                displayMovieCard($movie);
                            }
                        }
                    } else {
                        echo "<p class='text-center'>No positive reviews available.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Top Rated Movies -->
        <div class="card bg-secondary mb-3 mt-2" id="top-rated">
            <div class="card-header bg-warning">
                <h5 class="card-title text-center"><i class="bi bi-film"></i> Top Rated Movies </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    if ($top_rated_movies->num_rows > 0) {
                        while ($movie = $top_rated_movies->fetch_assoc()) {
                            if ($movie['review_count'] > 0 && $movie['average_rating'] > 3.5) {
                                displayMovieCard($movie);
                            }
                        }
                    } else {
                        echo "<p class='text-center'>No top-rated movies available.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="card bg-secondary mb-3" id="most-reviewed">
            <div class="card-header bg-dark text-center">
                <h5 class="card-title"><i class="bi bi-star-fill"></i> Most Reviewed Movies <i class="bi bi-star-fill"></i></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    if (isset($most_reviewed_movies) && $most_reviewed_movies->num_rows > 0) {
                        while ($movie = $most_reviewed_movies->fetch_assoc()) {
                            if ($movie['review_count'] > 1 && $movie['average_rating'] > 1) {
                                displayMovieCard($movie);
                            }
                        }
                    } else {
                        echo "<div class='col-12 text-center'><p>No most reviewed movies available.</p></div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- Movies Based on Your Watchlist Genres -->
        <div class="card bg-secondary" id="watchlist-genres">
            <div class="card-header bg-success text-center">
                <h5 class="card-title"><i class="bi bi-bookmark-star"></i> Genres based on your Watchlist <i class="bi bi-bookmark-star"></i></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php
                    // Check if there are movies and display them
                    if ($movies_by_genre && $movies_by_genre->num_rows > 0) {
                        while ($movie = $movies_by_genre->fetch_assoc()) {
                            // Call the displayMovieCard function for each movie
                            displayMovieCard($movie);
                        }
                    } else {
                        echo "<p class='text-center'>No movies available based on your watchlist genres.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"> </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"> </script>

</html>