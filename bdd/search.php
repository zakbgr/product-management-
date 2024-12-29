<?php
// Include necessary files and establish database connection
include('functions.php');
include('config.php');
// Initialize the search query variable
$search_query = '';

// Check if the search query is provided as a GET parameter
if (isset($_GET['query'])){
    // Sanitize the search query to prevent SQL injection
    $search_query = mysqli_real_escape_string($conn, $_GET['query']);

    // Perform the search query
    session_start();
    $user_id = $_SESSION['user_id'];
    $search_results = searchProducts($search_query, $conn, $user_id);

    // Display the search results
    if ($search_results) {
        // Output the search results
        echo " the result " ;
    } else {
        $error_message = "No products found.";
    }
} else {
    // If no search query is provided, redirect to the index page
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h2>Search Results</h2>
    <?php if(isset($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Price</th>
                <th>Taxes</th>
                <th>Ads</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Count</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($search_results as $result): ?>
                <tr>
                    <td><?php echo $result['title']; ?></td>
                    <td><?php echo $result['price']; ?></td>
                    <td><?php echo $result['taxes']; ?></td>
                    <td><?php echo $result['ads']; ?></td>
                    <td><?php echo $result['discount']; ?></td>
                    <td><?php echo $result['total']; ?></td>
                    <td><?php echo $result['count']; ?></td>
                    <td><?php echo $result['category']; ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $result['id']; ?>">Edit</a>
                        <a href="delete_product.php?id=<?php echo $result['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <a href="index.php">Return to Index</a>

</body>
</html>
