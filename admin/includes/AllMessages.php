<?php

session_start();
require_once 'db.php'; 
if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit;
}


$userId = $_SESSION['UserID'];
$stmt = $pdo->prepare("(SELECT * FROM messages WHERE recipient_id = :userId) UNION (SELECT * FROM messages WHERE sender_id = :userId) ORDER BY sent_at DESC");
$stmt->execute(['userId' => $userId]);
$allMessages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Messages</title>
    <link rel="stylesheet" href="styles.css">  
    
</head>
<body>

<div class="container">
    <div class="message-sidebar">
        <a href="compose.php">Compose</a>
        <a href="sent.php">Sent</a>
        <a href="inbox.php">Inbox</a>
        <a href="all_messages.php">All Messages</a>
    </div>

    <div id="main-content">
        <div class="content-section">
            <h2>All Messages</h2>
            <?php foreach ($allMessages as $message): ?>
                <div class="message-sidebar-nav">
        <a href="../includes/compose.php">Compose</a>
        <a href="../includes/sent.php">Sent</a><i class="fas fa-inbox"></i> 
        <a href="../includes/inbox.php">Inbox</a><i class="fas fa-paper-plane"></i>
        <a href="../includes/AllMessages.php">All Messages</a><i class="fas fa-paper-plane"></i> 
        </div>
            <?php endforeach; ?>
            <?php if (empty($allMessages)) echo "<p>No messages found.</p>"; ?>
        </div>
    </div>
</div>

</body>
</html>
