<?php
// Include necessary files and establish database connection
require_once 'config.php'; // Contains database connection settings
require_once 'functions.php'; // Contains reusable functions

// Initialize variables to store user information
$username = $email = '';

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Get user details from the database
$user_id = $_SESSION['user_id'];
$user = getUserById($user_id);

if ($user) {
    $username = $user['username'];
    $email = $user['email'];
} else {
    // Redirect to the login page if user details are not found
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Include any CSS stylesheets or external libraries here -->
</head>
<body>
    <h1>User Profile</h1>
    <p><strong>Username:</strong> <?php echo $username; ?></p>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    <!-- Include any additional profile information or actions here -->
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
