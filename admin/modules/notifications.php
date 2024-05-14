<?php
require_once '../includes/db.php'; // Adjust path as needed
session_start();

$loggedInUserId = $_SESSION['UserID']; // Ensure you're retrieving the logged-in user's ID correctly
$userType = $_SESSION['UserID']; // Example: 'Customer' or 'Employee', depending on your session setup

// Adjust the SQL query based on the user type
if ($userType === 'Customer') {
    $query = "SELECT * FROM notifications WHERE CustomerID = :UserID ORDER BY SentTime DESC";
} else if ($userType === 'Employee') {
    $query = "SELECT * FROM notifications WHERE EmployeeID = :UserID ORDER BY SentTime DESC";
} else {
    die("Invalid user type.");
}

$stmt = $pdo->prepare($query);
$stmt->bindParam(':UserID', $loggedInUserId, PDO::PARAM_INT);
$stmt->execute();
$notifications = $stmt->fetchAll();
?>
 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <link rel="stylesheet" href="path/to/your/css"> <!-- Adjust path as needed -->
</head>
<body>
     

<div class="notifications-container">
    <h2>Your Notifications</h2>
    <?php if (!empty($notifications)): ?>
        <ul class="notifications-list">
            <?php foreach ($notifications as $notification): ?>
                <li>
                    <strong><?php echo htmlspecialchars($notification['MessageType']); ?></strong>
                    <p><?php echo htmlspecialchars($notification['MessageContent']); ?></p>
                    <span>Received: <?php echo htmlspecialchars($notification['SentTime']); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No notifications found.</p>
    <?php endif; ?>
</div>


</body>
</html>
