<?php

include('functions.php');
// Include necessary files and establish database connection
require_once 'config.php'; // Contains database connection settings
require_once 'functions.php'; // Contains reusable functions

// Initialize variables to store user input and error messages
$username = $password = '';
$username_err = $password_err = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check if there are no input errors
    if (empty($username_err) && empty($password_err)) {
        // Check if the username and password are correct
        $user = getUserByUsername($username, $conn);
        if ($user && password_verify($password, $user['password'])) {
            // Start a new session and store user information
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            // Redirect to the index page or dashboard
            header("Location: index.php");
            exit();
        } else {
            // Display an error message if username or password is incorrect
            $password_err = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Loginstyle.css">
    <title>Login</title>
    <!-- Include any CSS stylesheets or external libraries here -->
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                <span><?php echo $username_err; ?></span>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Login">
            </div>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>