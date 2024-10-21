<?php
session_start(); // Start a session to store user data

include 'db_connect.php'; // Include database connection

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username/email and password from the POST request
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (!empty($username) && !empty($password)) {
        // Prepare a statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username); // Binding username and email

        // Execute the statement
        $stmt->execute();
        $stmt->store_result();

        // Check if a user exists
        if ($stmt->num_rows == 1) {
            // Bind result variables
            $stmt->bind_result($userId, $hashedPassword);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Store user data in session
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username; // Store username in session
                header("Location: index.php"); // Redirect to the main page
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with that username/email.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Please fill in both fields.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
