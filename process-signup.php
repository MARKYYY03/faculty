<?php

if (empty($_POST["username"])) {
    echo '<script>alert("Username is required"); history.go(-1);</script>';
    exit;
}
// The rest of your validation and insertion code

if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    echo '<script>alert("Valid email is required"); history.go(-1);</script>';
    exit;
}

if (strlen($_POST["password"]) < 8) {
    echo '<script>alert("Password must be at least 8 characters"); history.go(-1);</script>';
    exit;
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    echo '<script>alert("Password must contain at least one letter"); history.go(-1);</script>';
    exit;
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    echo '<script>alert("Password must contain at least one number"); history.go(-1);</script>';
    exit;
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    echo '<script>alert("Passwords must match"); history.go(-1);</script>';
    exit;
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Replace your existing database connection code with PDO
$host = "localhost";
$dbname = "ems_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check for duplicate email
$existingEmail = $_POST["email"];
$checkDuplicateSql = "SELECT COUNT(*) FROM user WHERE email = ?";
$checkDuplicateStmt = $pdo->prepare($checkDuplicateSql);
$checkDuplicateStmt->execute([$existingEmail]);
$count = $checkDuplicateStmt->fetchColumn();

if ($count > 0) {
    echo '<script>alert("Email is already registered. Please use a different email address."); history.go(-1);</script>';
    exit;
}

// Insert the new user
$insertSql = "INSERT INTO user (username, email, password_hash) VALUES (:username, :email, :password)";
$insertStmt = $pdo->prepare($insertSql);

if (!$insertStmt) {
    die("SQL error: " . $pdo->errorInfo()[2]);
}

$insertStmt->bindParam(':username', $_POST["username"]);
$insertStmt->bindParam(':email', $_POST["email"]);
$insertStmt->bindParam(':password', $password_hash);

if ($insertStmt->execute()) {
    header("Location: /FACULTY/login.php");
    exit;
} else {
    die("Error: " . $insertStmt->errorInfo()[2]);
}
