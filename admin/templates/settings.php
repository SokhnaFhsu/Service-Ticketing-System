<?php
session_start();
include '../includes/db.php'; // Include your database connection file

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $userId = $_SESSION['UserID'];

    // Prepare and execute SQL statement to update user profile
    $stmt = $pdo->prepare("UPDATE users SET Name = ?, Email = ?, Phone = ?, Address = ? WHERE UserID = ?");
    $stmt->execute([$name, $email, $phone, $address, $userId]);

    if ($stmt->rowCount() > 0) {
        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="../assets/css/profile.css"> <!-- Include your custom CSS file here -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Include Font Awesome for icons -->
</head>
<div class="profile-container">
    <h2>Profile Details</h2>
    <form id="profileForm">
        <div class="form-group">
            <label for="profileName">Name:</label>
            <input type="text" id="profileName" name="name" value="John Doe" readonly>
        </div>
        <div class="form-group">
            <label for="profileId">ID:</label>
            <input type="text" id="profileId" name="id" value="123456" readonly>
        </div>
        <div class="form-group">
            <label for="profileEmail">Email:</label>
            <input type="email" id="profileEmail" name="email" value="john.doe@example.com" readonly>
        </div>
        <div class="form-group">
            <label for="profilePhone">Phone:</label>
            <input type="tel" id="profilePhone" name="phone" value="+1234567890" readonly>
        </div>
        <div class="form-group">
            <label for="profileRole">Role:</label>
            <input type="text" id="profileRole" name="role" value="Administrator" readonly>
        </div>
        <button type="button" onclick="enableEditing()">Edit</button>
    </form>
</div>

<script>
function enableEditing() {
    document.querySelectorAll('#profileForm input').forEach(input => {
        if (input.name !== 'id' && input.name !== 'role') { // Assuming ID and Role are not editable
            input.readOnly = false;
        }
    });
}
</script>

</body>
</html>