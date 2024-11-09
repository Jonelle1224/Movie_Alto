<?php
include "/connect.php";

if (!isset($_SESSION['user'])) {
    header("Location: /user_data.php");
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Alto</title>
    <link rel="stylesheet" href="front/css/bootstrap.css">
    <link rel="stylesheet" href="front/css/bootstrap.min.css">
    <link rel="stylesheet" href="front/css/MovieAlto.css">
    <link rel="stylesheet" href="front/css/carousel.css">
</head>

<body>

    <?php include "front/frontbar.php"; ?>

    <div class="container">
        <h1>Welcome to Movie Alto, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <?php if (isset($_SESSION['watchlist_message'])): ?>
            <div class="alert alert-warning text-center">
                <?php echo $_SESSION['watchlist_message']; ?>
            </div>
            <?php unset($_SESSION['watchlist_message']); // Clear the message after displaying 
            ?>
        <?php endif; ?>

        <div class="text-center">
            <p>Explore our collection of movies and enjoy your experience!</p>
        </div>

        <hr>
        <h2>Featured Movies</h2>
            
        <!-- Movie carousel wrapper -->
        <div class="carousel-wrapper">
            <button class="carousel-btn left" onclick="scrollCarousel(-1)">&#8249;</button>
            <div class="movie-carousel" id="movieCarousel">
                <?php
                $query = "SELECT id, title, description, image FROM movies LIMIT 8"; // Select 15 movies
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($movie = mysqli_fetch_assoc($result)) {
                        if (isset($movie['id'])) {
                            $src = 'uploads/' . htmlspecialchars($movie['image']);
                            echo '
                            <div class="movie-card">
                                <div class="card">
                                    <img src="' . $src . '" class="card-img-top movie-poster" alt="' . htmlspecialchars($movie['title']) . '">
                                    <div class="card-body">
                                        <h5 class="card-title">' . htmlspecialchars($movie['title']) . '</h5>
                                        <hr class="divider">
                                        <a href="front/movie_details.php?id=' . htmlspecialchars($movie['id']) . '" class="btn btn-info btn-custom">View Details</a>
                                        <a href="front/add_watchlist.php?movie_id=' . htmlspecialchars($movie['id']) . '" class="btn btn-pink btn-custom">Add to Watch List</a>
                                    </div>
                                </div>
                            </div>';
                        } else {
                            echo '<div class="col-md-3 mb-4"><div class="card"><div class="card-body">Error: Movie ID is missing.</div></div></div>';
                        }
                    }
                } else {
                    echo '<div class="col-md-12"><div class="card"><div class="card-body">No movies found or error fetching movies: ' . mysqli_error($conn) . '</div></div></div>';
                }
                ?>
            </div>
            <button class="carousel-btn right" onclick="scrollCarousel(1)">&#8250;</button>
        </div>

    </div>
    <hr>
    <footer class="text-light footer text-center py-1">
        <p>&copy; <?php echo date("Y"); ?> Movie Alto. All rights reserved.</p>
        <a href="front/.about.php">About Us</a> |
        <a href="contact.php">FaQ</a>
    </footer>

    <script src="front/scripts/carousel.js"></script>
</body>

</html>