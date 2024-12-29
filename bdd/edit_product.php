<?php

$conn = mysqli_connect('localhost','root','123456','poodb');
$id = $title = $price = $taxes = $ads = $discount = $count = $category = '';
$errorMess = $successMess = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
        header("location: /bdd/index.php");
        exit;
    }

    $id = $_GET["id"];

    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /bdd/index.php");
        exit;
    }

    $title = $row["title"];
    $price = $row["price"];
    $taxes = $row["taxes"];
    $ads = $row["ads"];
    $discount = $row["discount"];
    $count = $row["count"];
    $category = $row["category"];

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST["id"];
    $title = $_POST["title"];
    $price = $_POST["price"];
    $taxes = $_POST["taxes"];
    $ads = $_POST["ads"];
    $discount = $_POST["discount"];
    $count = $_POST["count"];
    $category = $_POST["category"];

    if (empty($id)  && empty($title) && empty($price) &&  empty($taxes) && empty($ads)&& empty($discount)  && empty($count) && empty($category)) {
        $errorMess = "All fields are required";
    } else {
        $sql = "UPDATE products " .
            "SET title = ?, price = ?, taxes = ?, ads = ?, discount = ?, count = ?, category = ? " .
            "WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $title, $price, $taxes, $ads, $discount, $count, $category, $id);
        if ($stmt->execute()) {
            $successMess = "Product is updated";
            header("location: /bdd/index.php");
            exit;
        } else {
            $errorMess = "Invalid query: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
    <style>
        body {
            background-color: black;
            color: white; /* Text color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column; /* Align items vertically */
        }

        h1 {
            text-align: center;
            margin-bottom: 20px; /* Add space below the header */
        }

        form {
            width: 50%;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        span {
            color: red;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition-duration: 0.4s;
        }

        input[type="submit"]:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        .return-button {
            margin-top: 20px;
        }
        .return-button {
    display: inline-block;
    background-color: #4CAF50; /* Green */
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.return-button:hover {
    background-color: #45a049; /* Darker green on hover */
}
    </style>
</head>
<body>
    <h2>Update Product</h2>
    <?php if ($errorMess): ?>
        <p>Error: <?php echo $errorMess; ?></p>
    <?php endif; ?>
    <?php if ($successMess): ?>
        <p>Success: <?php echo $successMess; ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        Title: <input type="text" name="title" value="<?php echo $title; ?>"><br>
        Price: <input type="text" name="price" value="<?php echo $price; ?>"><br>
        Taxes: <input type="text" name="taxes" value="<?php echo $taxes; ?>"><br>
        Ads: <input type="text" name="ads" value="<?php echo $ads; ?>"><br>
        Discount: <input type="text" name="discount" value="<?php echo $discount; ?>"><br>
        Count: <input type="text" name="count" value="<?php echo $count; ?>"><br>
        Category: <input type="text" name="category" value="<?php echo $category; ?>"><br>
        <input type="submit" value="Update">
    </form>
    <a href="index.php" class="return-button">Return to Index</a>
</body>
</html>
