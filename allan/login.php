<?php
// Include database connection
include 'db_connect.php';

// Initialize variables
$email = '';
$password = '';
$message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        $message = "Please enter both email and password.";
    } else {
        // Prepare and execute SQL query to check credentials
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password (assuming passwords are hashed)
            if (password_verify($password, $user['password'])) {
                // Start session and set user data
                session_start();
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];

                // Redirect to homepage or desired page after login
                header("Location: index.php");
                exit;
            } else {
                $message = "Invalid email or password.";
            }
        } else {
            $message = "No user found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Movie Reviews</title>
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
    </style>
</head>
<body>
    <header>
        <h1>Login to Your Account</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Login</h2>
        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Login">
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
