<?php

$is_invalid = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Include the PDO database connection
    $pdo = require __DIR__ . "/database.php";

    // Check if email is provided
    if (empty($_POST["email"])) {
        $is_invalid = true;
        $error_message = "Email is required.";
    } elseif (empty($_POST["password"])) {
        // Check if password is provided
        $is_invalid = true;
        $error_message = "Password is required.";
    } else {
        // Use a prepared statement to prevent SQL injection
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindParam(':email', $_POST["email"]);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password using password_verify
            if (password_verify($_POST["password"], $user["password_hash"])) {
                // Start a session and regenerate the session ID
                session_start();
                session_regenerate_id();

                // Set the user ID in the session
                $_SESSION["user_id"] = $user["id"];

                header("Location: index.php");
                exit;
            }
        }

        $is_invalid = true;
        $error_message = "Invalid login";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/FACULTY/asset/css/login.css">
</head>
<body>
    
    <div class="container">
        <div class="left-container" id="loginContainer">
            <div class="image">
           <img src="/FACULTY/asset/image/leftside.png" alt="LEFTSIDE" /> 
           </div>
        </div>
        <div class="right-container">
            <div class="form">
        <form method="post">
        
        <div class="welcome">Welcome!</div>

                <?php if ($is_invalid): ?>
                    <div style="color: red;"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <div>
                    <label for="email"></label>
                    <input type="text" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
                </div>
                <div class="password-container">
                    <label for="password"></label>
                    <input type="password" id="password" name="password" placeholder="Password">
                    <!-- <input type="checkbox" id="show-password" onclick="togglePassword()">
                    <label for="show-password">Show Password</label> -->
                </div>
                <div class="login"><button>LOGIN</button></div>
              
            </form>
            <button><a href="index.php">SIGN UP?</a></button>
            </div>
        </div>
    </div>
    <script src="/FACULTY/asset/js/script.js"></script>
</body>
</html>
