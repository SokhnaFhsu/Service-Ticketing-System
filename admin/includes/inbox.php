<?php
// Start session and ensure user is authenticated
session_start();
require_once 'db.php'; // Make sure this points to your actual database connection file
$receiverId = $_SESSION['UserID']; // Modify this based on how you store the user's ID in the session

// Fetch received messages from the database
$sql = "SELECT message_id, sender_id, subject, body, sent_at, read_at FROM messages WHERE receiver_id = :receiverId ORDER BY sent_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['receiverId' => $receiverId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inbox</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="email-list-container">
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <div class="email-item <?php echo empty($message['read_at']) ? 'unread' : ''; ?>">
                <div class="email-item-header">
                    <span class="email-sender">From: <?php echo htmlspecialchars($message['sender_id']); // Ideally, fetch sender name from user table ?></span>
                    <span class="email-date"><?php echo htmlspecialchars(date("F j, Y, g:i a", strtotime($message['sent_at']))); ?></span>
                </div>
                <div class="email-item-body">
                    <span class="email-subject"><?php echo htmlspecialchars($message['subject']); ?></span>
                    <p class="email-preview"><?php echo nl2br(htmlspecialchars(substr($message['body'], 0, 100))) . '...'; // Preview first 100 characters ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No received messages.</p>
    <?php endif; ?>
</div>

</body>
</html>