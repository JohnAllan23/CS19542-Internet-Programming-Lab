<?php
// Include database connection
include 'db_connect.php';

// Fetch all movies from the database
$movies_query = "SELECT * FROM movies ORDER BY release_year DESC";
$result = $conn->query($movies_query); // Assign the result to $result

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Reviews - Home</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/scripts.js"></script>
    <script src="js/ajax.js"></script>
    <style>
        body {
            background-image: url('1.jpg'); /* Update the path to the image */
            background-size: cover; /* Ensures the image covers the entire background */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            background-position: center; /* Centers the background image */
            background-attachment: fixed; /* Keeps the background image fixed while scrolling */
            color: #ffffff;
        }

        header {
            display: flex;
            justify-content: space-between; /* Align title and nav to sides */
            align-items: center; /* Center vertically */
            padding: 10px 20px; /* Padding for header */
            background-color: #333; /* Background color for header */
            color: white; /* Text color */
        }

        header h1 {
            margin: 0; /* Remove default margin from h1 */
        }

        nav ul {
            list-style-type: none; /* Remove bullet points */
            padding: 0; /* Remove default padding */
            margin: 0; /* Remove default margin */
            display: flex; /* Display as a flexbox for horizontal layout */
            gap: 15px; /* Space between nav items */
        }

        nav a {
            color: white; /* Text color for links */
            text-decoration: none; /* Remove underline from links */
        }

        nav a:hover {
            text-decoration: underline; /* Underline on hover */
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Movie Reviews</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="submit_review.php">Submit a Review</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Featured Movies</h2>
            <div class="movie-list">
                <?php while ($movie = $result->fetch_assoc()): ?>
                    <div class="movie">
                        <h3><a href="movie.php?id=<?php echo $movie['movie_id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></a></h3>
                        <p><strong>Release Year:</strong> <?php echo htmlspecialchars($movie['release_year']); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
                    </div>
                    <hr>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No movies found.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Movie Reviews. All Rights Reserved.</p>
    </footer>

    <?php
    // Close database connection
    $conn->close();
    ?>
</body>
</html>