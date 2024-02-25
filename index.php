<?php

session_start();

if (isset($_SESSION["user_id"])) {
    // Include the PDO database connection
    $pdo = require __DIR__ . "/database.php";
    
    // Use a prepared statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION["user_id"]);
    $stmt->execute();
    
    // Fetch the user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="">
    <style>
    .input-with-icon {
      position: relative;
    }

    .input-with-icon input {
      padding-left: 30px; /* Adjust the padding to make space for the icon */
      background: url('/FACULTY/asset/image/person.png') no-repeat 5px center; /* Specify the path to your icon */
      background-size: 20px 20px; /* Adjust the size of your icon */
    }
  </style>
</head>
<body>
    
    <?php if (isset($user)): ?>
        
         
    index

        
    <?php else: ?>

        <div class="container">
      <div class="right-container">
        <h1>Embark on your 
FITNESS journey 
with us</h1>
        <button><a href="login.php">SIGN IN?</a></button>
      </div>
      <div class="left-container" id="registerContainer">
        <form action="process-signup.php" method="post" id="signup" novalidate>
          <h1>Sign up</h1>
          <div class="registerform">
            <label for="firstname">Firstname</label>
            <input
              type="text"
              id="firstname"
              name="firstname"
            />
            <div class="input-with-icon">
    <label for="lastname">Lastname</label>
    <input
      type="text"
      id="lastname"
      name="lastname"
      placeholder=""
    />
  </div>

            <label for="email">Email</label>
            <input type="text" id="email" name="email" 
            <label for="password"></label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Password"
            />
            <input
              type="password"
              id="password_confirmation"
              name="password_confirmation"
              placeholder="Confirm Password"
            />
            <input
              type="checkbox"
              id="show-register-password"
              onclick="toggleRegisterPassword()"
            />
            <label for="show-register-password">Show Password</label>
          </div>
          <div class="sumbit">
          <p class="error-message" id="errorMessage"></p>
            <button>Sign up</button>
          </div>
        </form>
      </div>
    </div>

    <?php endif; ?>
    <script src="/FACULTY/asset/js/script.js"></script>
</body>
</html>