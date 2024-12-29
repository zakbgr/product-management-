<?php

$conn = mysqli_connect('localhost','root','123456','poodb');
// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '123456';
$db_name = 'poodb';
$user_id ;
/**
 * Search for products based on the given query.
 *
 * @param string $query The search query.
 * @param mysqli $conn The database connection.
 * @return array|false An array of search results or false if no results found.
 */
// Establish database connection
function connectDB() {
    global $db_host, $db_username, $db_password, $db_name;
    $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}
function getUserByUsername($username, $conn) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user; // Retourne l'utilisateur s'il est trouvé, sinon retourne null
}
function getUserByEmail($email, $conn) {
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user;
}
// Fetch products from the database
function getProducts($user_id) {
     $conn = connectDB(); // Assuming $conn is your database connection

    // Prepare the SQL statement with a WHERE clause to filter by user_id
    $sql = "SELECT * FROM products WHERE user_id = ?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);
    
    // Bind the user_id parameter
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch products as an associative array
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Free the statement
    mysqli_stmt_close($stmt);

    return $products;
}


// Get user by ID
function getUserById($user_id) {
    $conn = connectDB();
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $user;
}

// Add new user
function addUser($username, $email, $password) {
    $conn = connectDB();
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

// Get product by ID
function getProductById($product_id) {
    $conn = connectDB();
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $product;
}

// Add new product
function addProduct($user_id, $title, $price, $taxes, $ads, $discount, $count, $category) {
    $total = $price + $taxes + $ads - $discount; // Calcul du total en fonction des autres valeurs
    $conn = mysqli_connect('localhost', 'root', '123456', 'poodb');
    $sql = "INSERT INTO products (user_id, title, price, taxes, ads, discount, total, count, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isdddddds", $user_id, $title, $price, $taxes, $ads, $discount, $total, $count, $category);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}
function updateProduct($id, $user_id, $title, $price, $taxes, $ads, $discount, $total, $count, $category) {
    $conn = connectDB();
    $sql = "UPDATE products SET user_id = ?, title = ?, price = ?, taxes = ?, ads = ?, discount = ?, total = ?, count = ?, category = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sdddddis", $title, $price, $taxes, $ads, $discount, $total, $count, $category);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

// Delete product
function deleteProduct($product_id) {
    $conn = connectDB();
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}
function searchProducts($query, $conn,$userId) {
    // Query to search for products based on title or category
    $sql = "SELECT * FROM products WHERE (title LIKE '%$query%') AND user_id = $userId ";

    // Perform the query
    $result = mysqli_query($conn, $sql);

    // Check if there are any search results
    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the search results as an associative array
        $search_results = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $search_results;
    } else {
        // No search results found
        return false;
    }
}
?>
