<?php
include('functions.php');
// Inclure les fichiers nécessaires
require_once 'config.php'; // Contient les paramètres de connexion à la base de données
require_once 'functions.php'; // Contient les fonctions réutilisables

// Initialiser les variables pour stocker les données de l'utilisateur et les messages d'erreur
$username = $email = $password = $confirm_password = '';
$username_err = $email_err = $password_err = $confirm_password_err = '';



// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider le nom d'utilisateur
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        // Vérifier si le nom d'utilisateur est déjà pris
        $existing_user = getUserByUsername($_POST["username"], $conn);
        if ($existing_user) {
            $username_err = "This username is already taken.";
        } else {
            $username = trim($_POST["username"]);
        }
    }

    // Valider l'e-mail
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        // Vérifier si l'e-mail est déjà enregistré
        $existing_email = getUserByEmail($_POST["email"] , $conn);
        if ($existing_email) {
            $email_err = "This email is already registered.";
        } else {
            $email = trim($_POST["email"]);
        }
    }

    // Valider le mot de passe
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "Password must have at least 8 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Valider la confirmation du mot de passe
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Vérifier s'il n'y a pas d'erreurs de saisie
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // Hasher le mot de passe avant de l'enregistrer dans la base de données
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Insérer un nouvel utilisateur dans la base de données
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
            $param_username = $username;
            $param_email = $email;
            $param_password = $hashed_password;
            if (mysqli_stmt_execute($stmt)) {
                // Rediriger vers la page de connexion après une inscription réussie
                header("Location: index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

        input[type="text"],
        input[type="Email"],
        input[type="password"] {
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
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Register</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>">
                <span><?php echo $username_err; ?></span>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>">
                <span><?php echo $email_err; ?></span>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <span><?php echo $confirm_password_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Register">
            </div>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
