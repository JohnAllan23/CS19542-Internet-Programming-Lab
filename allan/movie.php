<?php
// Include database connection
include 'db_connect.php';

// Check if a movie ID is provided via the URL
if (isset($_GET['id'])) {
    $movie_id = intval($_GET['id']);

    // Fetch the movie details
    $movie_query = "SELECT * FROM movies WHERE movie_id = ?";
    $stmt = $conn->prepare($movie_query);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $movie_result = $stmt->get_result();

    // Fetch the reviews for the movie
    $review_query = "SELECT * FROM reviews WHERE movie_id = ? ORDER BY created_at DESC";
    $review_stmt = $conn->prepare($review_query);
    $review_stmt->bind_param("i", $movie_id);
    $review_stmt->execute();
    $review_result = $review_stmt->get_result();

    // Check if the movie exists
    if ($movie_result->num_rows > 0) {
        $movie = $movie_result->fetch_assoc();
    } else {
        echo "<p>Movie not found.</p>";
        exit;
    }
} else {
    echo "<p>No movie ID provided.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?> - Movie Reviews</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/scripts.js"></script><script src="js/ajax.js"></script>
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="submit_review.php?id=<?php echo $movie['movie_id']; ?>">Submit a Review</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Description</h2>
        <p><?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>

        <h2>Reviews</h2>
        <?php if ($review_result->num_rows > 0): ?>
            <div class="review-list">
                <?php while ($review = $review_result->fetch_assoc()): ?>
                    <div class="review">
                        <p><strong>Review:</strong> <?php echo htmlspecialchars($review['review_text']); ?></p>
                        <p><strong>Rating:</strong> <?php echo htmlspecialchars($review['rating']); ?> / 5</p>
                        <p><em>Posted on: <?php echo htmlspecialchars($review['created_at']); ?></em></p>
                        <hr>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No reviews for this movie yet.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Movie Reviews. All Rights Reserved.</p>
    </footer>

    <?php
    // Close database connection
    $stmt->close();
    $review_stmt->close();
    $conn->close();
    ?>
</body>
</html>
