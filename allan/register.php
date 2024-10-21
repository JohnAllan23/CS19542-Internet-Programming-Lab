<?php
session_start(); // Start a session

include 'db_connect.php'; // Include database connection

// Initialize variables
$username = $email = $password = $confirm_password = '';
$message = '';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input from the POST request
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username or email already exists.";
            $stmt->close();
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL statement to insert the new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $conn->insert_id; // Store the new user's ID in the session
                $_SESSION['username'] = $username; // Store username in the session
                
                // Set success message
                $message = "Registration successful! You will be redirected shortly.";
                $stmt->close();
            } else {
                $message = "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Movie Reviews</title>
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
        /* Additional CSS for title bar and navigation */
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

        /* Style for message */
        .message {
            color: green; /* Change to green for success */
            font-weight: bold;
        }

        .error-message {
            color: red; /* Change to red for errors */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Create an Account</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Register</h2>
        
        <?php if (!empty($message)): ?>
            <p class="<?php echo (strpos($message, 'successful') !== false) ? 'message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
            <?php if (strpos($message, 'successful') !== false): ?>
                <script>
                    // Redirect to home after 3 seconds if registration is successful
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 3000);
                </script>
            <?php endif; ?>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>

            <input type="submit" value="Register">
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Movie Reviews. All Rights Reserved.</p>
    </footer>
</body>
</html>
