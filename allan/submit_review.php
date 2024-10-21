<?php
// Include database connection
include 'db_connect.php';

// Check if a movie ID is provided
if (isset($_GET['id'])) {
    $movie_id = intval($_GET['id']);
} else {
    echo "<p>No movie ID provided.</p>";
    exit;
}

// Initialize variables
$review_text = '';
$rating = 1; // Default rating
$message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $review_text = trim($_POST['review_text']);
    $rating = intval($_POST['rating']);

    // Validate inputs
    if (empty($review_text)) {
        $message = "Review cannot be empty.";
    } elseif ($rating < 1 || $rating > 5) {
        $message = "Rating must be between 1 and 5.";
    } else {
        // Insert review into the database
        $insert_query = "INSERT INTO reviews (movie_id, review_text, rating, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("isi", $movie_id, $review_text, $rating);
        
        if ($stmt->execute()) {
            $message = "Review submitted successfully!";
            // Optionally redirect to the movie page after submission
            header("Location: movie.php?id=" . $movie_id);
            exit;
        } else {
            $message = "Error submitting review: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Review - Movie Reviews</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/scripts.js"></script><script src="js/ajax.js"></script>
</head>
<body>
    <header>
        <h1>Submit Review for Movie ID: <?php echo htmlspecialchars($movie_id); ?></h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Submit Your Review</h2>
        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $movie_id; ?>" method="POST">
            <label for="review_text">Review:</label><br>
            <textarea id="review_text" name="review_text" rows="4" required><?php echo htmlspecialchars($review_text); ?></textarea><br><br>

            <label for="rating">Rating:</label><br>
            <select id="rating" name="rating" required>
                <option value="1" <?php echo ($rating == 1) ? 'selected' : ''; ?>>1</option>
                <option value="2" <?php echo ($rating == 2) ? 'selected' : ''; ?>>2</option>
                <option value="3" <?php echo ($rating == 3) ? 'selected' : ''; ?>>3</option>
                <option value="4" <?php echo ($rating == 4) ? 'selected' : ''; ?>>4</option>
                <option value="5" <?php echo ($rating == 5) ? 'selected' : ''; ?>>5</option>
            </select><br><br>

            <input type="submit" value="Submit Review">
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Movie Reviews. All Rights Reserved.</p>
    </footer>

    <?php
    // Close database connection
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
