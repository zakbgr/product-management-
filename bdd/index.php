<?php
session_start();
include('functions.php');
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch products associated with the logged-in user
$user_id = $_SESSION['user_id'];
$products = getProducts($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management System</title>
    <link rel="stylesheet" href="index.css">
    <style>
     
    </style>
</head>
<body>
    <h1>Welcome to the Product Management System</h1>
       <div class="search-bar">
        <form action="search.php" method="get">
            <input type="text" name="query" placeholder="Search products...">
            <input type="submit" value="Search">
            <a href="add_product.php">Add Product</a>
   
    </div>
    <h2>Products</h2>
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
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['title']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['taxes']; ?></td>
                    <td><?php echo $product['ads']; ?></td>
                    <td><?php echo $product['discount']; ?></td>
                    <td><?php echo $product['total']; ?></td>
                    <td><?php echo $product['count']; ?></td>
                    <td><?php echo $product['category']; ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                        <a href="delete_product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="logout.php">Logout</a>

    <!-- Include any JavaScript files or scripts here -->
</body>
</html>
