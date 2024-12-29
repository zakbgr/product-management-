<?php
session_start(); // Start the session

include('functions.php');
require_once 'config.php'; // Contains database connection settings

// Initialize variables to store user input and error messages
$title = $price = $taxes = $ads = $discount = $count = $category = '';
$title_err = $price_err = $taxes_err = $ads_err = $discount_err = $count_err = $category_err = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }

    // Validate price
    if (empty(trim($_POST["price"]))) {
        $price_err = "Please enter a price.";
    } elseif (!is_numeric($_POST["price"])) {
        $price_err = "Price must be a number.";
    } else {
        $price = trim($_POST["price"]);
    }

    // Validate taxes
    if (empty(trim($_POST["taxes"]))) {
        $taxes_err = "Please enter taxes.";
    } elseif (!is_numeric($_POST["taxes"])) {
        $taxes_err = "Taxes must be a number.";
    } else {
        $taxes = trim($_POST["taxes"]);
    }

    // Validate ads
    if (empty(trim($_POST["ads"]))) {
        $ads_err = "Please enter ads cost.";
    } elseif (!is_numeric($_POST["ads"])) {
        $ads_err = "Ads cost must be a number.";
    } else {
        $ads = trim($_POST["ads"]);
    }

    // Validate discount
    if (empty(trim($_POST["discount"]))) {
        $discount_err = "Please enter a discount.";
    } elseif (!is_numeric($_POST["discount"])) {
        $discount_err = "Discount must be a number.";
    } else {
        $discount = trim($_POST["discount"]);
    }

    // Validate count
    if (empty(trim($_POST["count"]))) {
        $count_err = "Please enter a count.";
    } elseif (!ctype_digit($_POST["count"])) {
        $count_err = "Count must be a positive integer.";
    } else {
        $count = trim($_POST["count"]);
    }

    // Validate category
    if (empty(trim($_POST["category"]))) {
        $category_err = "Please enter a category.";
    } else {
        $category = trim($_POST["category"]);
    }

    // Check for any input errors
    if (empty($title_err) && empty($price_err) && empty($taxes_err) && empty($ads_err) && empty($discount_err) && empty($count_err) && empty($category_err)) {
        // Insert new product into the database
        $user_id = $_SESSION['user_id']; // Récupérer l'identifiant de l'utilisateur à partir de la session
        if (addProduct($user_id, $title, $price, $taxes, $ads, $discount, $count, $category)) {
            // Redirection vers la page d'index après l'ajout réussi
            header("Location: index.php");
            exit();
        } else {
            echo "Une erreur s'est produite. Veuillez réessayer plus tard.";
        }        
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            background-color: black;
            color: white; /* Text color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            text-align: center;
        }

        input[type="text"] {
            background-color: transparent; /* Set background color to transparent */
            border: 1px solid #4CAF50; /* Green border */
            color: white;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border-radius: 8px;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            transition-duration: 0.4s;
        }
input[type="submit"]:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        /* 3D Button Effect */
        input[type="submit"] {
            box-shadow: 0 9px #999;
            transform: translateY(1px);
        }

        input[type="submit"]:active {
            background-color: #3e8e41; /* Clicked color */
            box-shadow: 0 5px #666;
            transform: translateY(3px);
        }

        span.error-message {
            color: red;
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
    <div class="form-container">
        <h1>Add Product</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $title; ?>">
                <span class="error-message"><?php echo $title_err; ?></span>
            </div>
            <div>
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" value="<?php echo $price; ?>">
                <span class="error-message"><?php echo $price_err; ?></span>
            </div>
            <div>
                <label for="taxes">Taxes:</label>
                <input type="text" id="taxes" name="taxes" value="<?php echo $taxes; ?>">
                <span class="error-message"><?php echo $taxes_err; ?></span>
            </div>
            <div>
                <label for="ads">Ads Cost:</label>
                <input type="text" id="ads" name="ads" value="<?php echo $ads; ?>">
                <span class="error-message"><?php echo $ads_err; ?></span>
            </div>
            <div>
                <label for="discount">Discount:</label>
                <input type="text" id="discount" name="discount" value="<?php echo $discount; ?>">
                <span class="error-message"><?php echo $discount_err; ?></span>
            </div>
            <div>
                <label for="count">Count:</label>
                <input type="text" id="count" name="count" value="<?php echo $count; ?>">
                <span class="error-message"><?php echo $count_err; ?></span>
            </div>
            <div>
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" value="<?php echo $category; ?>">
                <span class="error-message"><?php echo $category_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Add Product">
            </div>
        </form>
        <!-- Return to Index button -->
        <a href="index.php" class="return-button">Return to Index</a>
    </div>
</body>
</html>
