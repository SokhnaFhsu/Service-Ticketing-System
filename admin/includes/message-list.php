<div class="message-container">
    <div class="message-header">
        <h1>Inbox</h1>
    </div>
    <div class="message-list">
        <!-- Dynamically generated message list goes here -->
        <?php foreach ($messages as $message): ?>
            <div class="message-preview" onclick="location.href='message_detail.php?id=<?php echo $message['message_id']; ?>'">
                <div class="sender">From: User ID <?php echo $message['sender_id']; ?></div>
                <div class="subject"><?php echo $message['subject']; ?></div>
                <div class="body-preview"><?php echo substr($message['body'], 0, 100) . '...'; ?></div>
                <div class="sent-time"><?php echo $message['sent_at']; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
