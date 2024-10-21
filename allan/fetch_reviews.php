<?php
include 'db_connect.php'; // Include database connection

// Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the movie_id from the query parameters
    if (isset($_GET['movie_id'])) {
        $movie_id = (int)$_GET['movie_id'];

        // Prepare a statement to fetch reviews for the given movie_id
        $stmt = $conn->prepare("SELECT r.id, r.review_text, r.rating, u.username, r.created_at 
                                  FROM reviews r 
                                  JOIN users u ON r.user_id = u.id 
                                  WHERE r.movie_id = ? 
                                  ORDER BY r.created_at DESC");
        $stmt->bind_param("i", $movie_id); // Bind the movie_id parameter

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch all reviews
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }

        // Return reviews as JSON
        header('Content-Type: application/json');
        echo json_encode($reviews);
    } else {
        echo json_encode(["error" => "Movie ID is required."]);
    }
} else {
    echo json_encode(["error" => "Invalid request method."]);
}

// Close the database connection
$conn->close();
?>
