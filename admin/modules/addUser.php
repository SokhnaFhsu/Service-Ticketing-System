<?php
require_once '../includes/db.php'; // Adjust the path as necessary

if ($_SERVER["REQUEST_METHOD"] == "POST" && isAdmin()) { // Ensure this is an admin
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']); // Optional

    // Check if username already exists
    $sql = "SELECT id FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    if ($stmt->rowCount() > 0) {
        echo "Username already exists.";
    } else {
        // Hash password and insert new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username, 'password' => $hashed_password, 'email' => $email]);
        echo "User created successfully.";
    }
}

function isAdmin() {
    // Implement check to determine if the current user is an admin
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
}
?>

