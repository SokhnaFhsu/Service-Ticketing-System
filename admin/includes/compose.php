<?php
// Start session and ensure user is authenticated
session_start();
require_once 'db.php'; // Ensure this points to your actual database connection file
if (!isset($_SESSION['UserID'])) {
    header('Location: .login.php');
    exit;
}

// Add any PHP logic you need for handling form submission here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Compose Message</title>
    <link rel="stylesheet" href="../assets/css/admin.css"> 
</head>
<body>

<div class="container">
    <div class="message-sidebar">
    <div class="message-sidebar-header">
            <h2>Messages</h2> <!-- Sidebar Header -->
        </div>
        
        <div class="message-sidebar-nav">
        <a href="../includes/compose.php">Compose</a>
        <a href="../includes/sent.php">Sent</a><i class="fas fa-inbox"></i> 
        <a href="../includes/inbox.php">Inbox</a><i class="fas fa-paper-plane"></i>
        <a href="../includes/AllMessages.php">All Messages</a><i class="fas fa-paper-plane"></i> 
        </div>
    </div>

    <div id="main-content">
        <div class="content-section">
            <h2>Compose New Message</h2>
            <form id="compose-form" action="send_message.php" method="POST">
                <div class="form-group">
                    <label for="to">To:</label>
                    <input type="email" id="to" name="to" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
