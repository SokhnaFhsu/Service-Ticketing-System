<?php
session_start();
require_once 'db.php'; // Adjust the path as needed

$senderId = $_SESSION['UserID']; // Change this according to how you store logged-in user's ID

// Fetch sent messages from the database
$sql = "SELECT message_id, receiver_id, subject, body, sent_at, read_at FROM messages WHERE sender_id = :senderId ORDER BY sent_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['senderId' => $senderId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sent Messages</title>
    <link rel="stylesheet" href="../assets/css/admin.css"> <!-- Ensure this path is correct -->
</head>
<body>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

.email-list-container {
    width: 100%;
    max-width: 800px;
    margin: auto;
}

.email-item {
    background-color: #fff;
    margin-bottom: 10px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.email-item:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.email-item-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.email-recipient, .email-date {
    color: #333;
    font-size: 0.9em;
}

.email-subject {
    font-size: 1.1em;
    font-weight: bold;
}

.email-preview {
    font-size: 0.9em;
    color: #666;
}

        </style>



<div class="email-list-container">
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <div class="email-item">
                <div class="email-item-header">
                    <span class="email-recipient">To: <?php echo htmlspecialchars($message['receiver_id']); // Fetch and display receiver name if available ?></span>
                    <span class="email-date"><?php echo htmlspecialchars(date("F j, Y, g:i a", strtotime($message['sent_at']))); ?></span>
                </div>
                <div class="email-item-body">
                    <span class="email-subject"><?php echo htmlspecialchars($message['subject']); ?></span>
                    <p class="email-preview"><?php echo nl2br(htmlspecialchars($message['body'])); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No sent messages.</p>
    <?php endif; ?>
</div>

</body>
</html>