<?php
// Start the session if it has not been started already


include "../include/connect.php";

if (isset($_GET['reviewid'])) {
    $movie_id = $_GET['reviewid'];
} else {
    die("Movie ID is not set.");
}

// Query to fetch movie details
$movie_query = "SELECT * FROM movies WHERE ID = $movie_id";
$movie_result = $conn->query($movie_query);

if ($movie_result) {
    if ($movie_result->num_rows > 0) {
        $movie = $movie_result->fetch_assoc();
        $src = 'uploads/' . htmlspecialchars($movie['image']); // Adjust for your image type
    } else {
        echo "<div class='alert alert-danger'>No movie found with ID: " . $movie_id . "</div>";
        exit();
    }
} else {
    die("Query failed: " . $conn->error);
}

// Query to fetch reviews for the movie
$review_query = "SELECT rr.*, u.profile_image FROM review_rate rr LEFT JOIN users u ON rr.user_id = u.ID WHERE rr.movie_id = $movie_id ORDER BY rr.ID DESC";
$reviews_result = $conn->query($review_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['username'];
        $rating = $_POST['rating'];
        $review = $conn->real_escape_string($_POST['review']);

        $command = escapeshellcmd("python ../pyt/testvade.py \"$review\"");
        $output = shell_exec($command);

        
        file_put_contents('debug_log.txt', "Raw output: " . print_r($output, true) . "\n", FILE_APPEND);

        if ($output !== null && preg_match('/Sentiment Label: (.+), Compound Score: (.+)/', trim($output), $matches)) {
            $sentiment_label = trim($matches[1]);
            $compound_score = trim($matches[2]);

            file_put_contents('debug_log.txt', "Sentiment Label: $sentiment_label, Compound Score: $compound_score\n", FILE_APPEND);
        } else {
            file_put_contents('debug_log.txt', "Unexpected output format or empty output: $output\n", FILE_APPEND);

            $sentiment_label = 'neutral';
            $compound_score = 0.0; 
        }

        $check_query = "SELECT * FROM review_rate WHERE movie_id = $movie_id AND user_id = '$user_id'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows === 0) {
            // Insert new review with sentiment label and score
            $insert_query = "INSERT INTO review_rate (user_id, user_name, movie_id, reviews, ratings, sentiment_label, sentiment_score) 
                     VALUES ('$user_id', '$user_name', '$movie_id', '$review', '$rating', '$sentiment_label', '$compound_score')";

            if ($conn->query($insert_query) === TRUE) {
                // Redirect with success message
                header("Location: user_review.php?reviewid=" . $movie_id . "&message=Review submitted successfully.");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            // Update existing review
            $update_query = "UPDATE review_rate SET reviews = '$review', ratings = '$rating', sentiment_label = '$sentiment_label', sentiment_score = '$compound_score'
                     WHERE movie_id = $movie_id AND user_id = '$user_id'";

            if ($conn->query($update_query) === TRUE) {
                // Redirect with success message
                header("Location: user_review.php?reviewid=" . $movie_id . "&message=Review updated successfully.");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } else {
        echo "You must be logged in to submit a review.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?> - Movie Details</title>
    <link rel="stylesheet" href="front/css/bootstrap.min.css">
    <link rel="stylesheet" href="front/css/styles.css"> <!-- Custom CSS for additional styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/starscript.css">
    <style>
        .rating {
            direction: rtl;
            /* To reverse the order */
            display: flex;
            justify-content: center;
            /* Align to the center */
        }

        .rating input {
            display: none;
            /* Hide radio buttons */
        }

        .rating label {
            font-size: 30px;
            /* Size of the stars */
            color: #ddd;
            /* Default star color */
            cursor: pointer;
        }

        .rating input:checked~label {
            color: gold;
            /* Color of filled stars */
        }

        /* Change color on hover */
        .rating label:hover,
        .rating label:hover~label {
            color: gold;
            /* Highlight stars on hover */
        }
    </style>
</head>

<body>
    <!-- Include the navigation bar -->
    <?php include "frontbar.php"; ?>

    <div class="container mt-2">
        <div class="d-flex justify-content-between align-items-center">
            <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
            <a href="../MovieAlto.php" class="btn btn-info">Back to Movies</a>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <img src="<?php echo $src; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($movie['title']); ?>" style="width: 350px; height: 500px; object-fit: cover;">
            </div>

            <div class="container justify-content-center mt-1 col-md-8">
                <div class="row">
                    <h2>Reviews:</h2>
                    <div class="card">
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            <?php if ($reviews_result && $reviews_result->num_rows > 0): ?>
                                <?php while ($review = $reviews_result->fetch_assoc()): ?>
                                    <div class="card bg-primary mb-3">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($review['profile_image'])): ?>
                                                    <img src="uploads/<?php echo htmlspecialchars($review['profile_image']); ?>" alt="Profile Image" class="rounded-circle" style="width: 85px; height: 85px; margin-right: 20px; margin-top: -10px;">
                                                <?php else: ?>
                                                    <img src="uploads/default_profile.png" alt="Default Profile Image" class="rounded-circle" style="width: 50px; height: 50px; margin-right: 10px; margin-top: -10px;">
                                                <?php endif; ?>
                                                <div>
                                                    <h5 class="card-title">User: <?php echo htmlspecialchars($review['user_name']); ?></h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">Rating: <?php echo htmlspecialchars($review['ratings']); ?>/5</h6>
                                                    <p class="card-text"><?php echo htmlspecialchars($review['reviews']); ?></p>
                                                    <?php if ($review['user_id'] == $_SESSION['user_id']): ?>
                                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editReviewModal"
                                                            data-reviewid="<?php echo $review['ID']; ?>"
                                                            data-review="<?php echo htmlspecialchars($review['reviews']); ?>"
                                                            data-rating="<?php echo htmlspecialchars($review['ratings']); ?>">Edit Review</button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-muted">
                                            <small>Posted on: <?php echo htmlspecialchars($review['created_at']); ?></small>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-center">No reviews yet. Be the first to review!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row mt-4 justify-content-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="col-auto"></div>
                    <?php else: ?>
                        <p>You must be logged in to submit a review.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <div class="container justify-content-center mt-3">
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#submitReviewModal">
            Submit Your Review <i class="bi bi-star-fill mr-2"></i>
        </button>
    </div>

    <!-- Submit Review Modal -->
    <div class="modal fade" id="submitReviewModal" tabindex="-1" aria-labelledby="submitReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitReviewModalLabel">Submit Your Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rating">Rate from 1 to 5:</label>
                            <!-- Added title for the rating -->

                            <div class="rating">
                                <input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
                                <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                                <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                                <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                                <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="review">Your Review:</label>
                            <textarea class="form-control" name="review" id="review" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Review Modal -->
    <div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="editReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReviewModalLabel">Edit Your Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editReviewForm" action="" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editRating">Rate from 1 to 5:</label>

                            <div class="rating">
                                <input type="radio" name="rating" value="5" id="editStar5"><label for="editStar5">★</label>
                                <input type="radio" name="rating" value="4" id="editStar4"><label for="editStar4">★</label>
                                <input type="radio" name="rating" value="3" id="editStar3"><label for="editStar3">★</label>
                                <input type="radio" name="rating" value="2" id="editStar2"><label for="editStar2">★</label>
                                <input type="radio" name="rating" value="1" id="editStar1"><label for="editStar1">★</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editReview">Your Review:</label>
                            <textarea class="form-control" name="review" id="editReview" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="review_id" id="review_id" value="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="front/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script to populate edit review modal with existing values
        const editReviewModal = document.getElementById('editReviewModal');
        editReviewModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const reviewId = button.getAttribute('data-reviewid');
            const reviewText = button.getAttribute('data-review');
            const ratingValue = button.getAttribute('data-rating');

            // Populate the modal with existing review data
            document.getElementById('review_id').value = reviewId;
            document.getElementById('editReview').value = reviewText;
            document.querySelector(`input[name="rating"][value="${ratingValue}"]`).checked = true; // Set the radio button
        });
    </script>
    <script src=scripts/user_reviews.js></script>
</body>

</html>