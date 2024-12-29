<?php
// Include necessary files and establish database connection
require_once 'config.php'; // Contains database connection settings
require_once 'functions.php'; // Contains reusable functions

// Check if product ID is provided as a query parameter
if (isset($_GET['id'])) {
    // Get the product ID from the query parameter
    $product_id = $_GET['id'];

    // Delete the product from the database
    if (deleteProduct($product_id)) {
        // Redirect to the index page after successful deletion
        header("Location: index.php");
        exit();
    } else {
        echo "Something went wrong. Please try again later.";
    }
} else {
    // Redirect to the index page if product ID is not provided
    header("Location: index.php");
    exit();
}
?>
