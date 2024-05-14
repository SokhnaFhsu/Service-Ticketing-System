<?php
session_start();
require '../includes/db.php'; // Ensure this points to your database connection file.

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit;
}

// If 'box' is not set or is empty, we won't include any specific file by default.
$box = $_GET['box'] ?? '';

// Header and sidebar are included on all pages for consistent navigation.


?>

<div id="main-content">
    <?php
    // Only include content when 'box' parameter is set.
    if (!empty($box)) {
        switch ($box) {
            case 'compose':
                include('../includes/compose.php');
                break;
            case 'sent':
                include('../includes/sent.php');
                break;
            case 'inbox':
                include('../includes/inbox.php');
                break;
            case 'all':
                include('../includes/AllMessages.php');
                break;
            // ...other cases for different content...
            default:
                // You can either show a default page or nothing at all.
                echo "<p>Select an option from the menu.</p>";
        }
    } else {
        
    }
    ?>
</div>

<?php
include '../includes/footer.php'; // Include the footer if you have one.

    

// Function to fetch message counts
function fetchMessageCounts($pdo, $userID) {
    // Count for inbox messages
    $inboxStmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE receiver_id = :UserID");
    $inboxStmt->execute(['UserID' => $userID]);
    $inboxCount = $inboxStmt->fetchColumn();
    
    // Count for sent messages
    $sentStmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE sender_id = :UserID");
    $sentStmt->execute(['UserID' => $userID]);
    $sentCount = $sentStmt->fetchColumn();
    
    // Total count (assuming all messages are either sent or received by the user)
    $allCount = $inboxCount + $sentCount;
    
    return [
        'inboxCount' => $inboxCount,
        'sentCount'  => $sentCount,
        'allCount'   => $allCount
    ];
}

// Fetch the message counts
$userID = $_SESSION['UserID'];
$counts = fetchMessageCounts($pdo, $userID);

// Define which box to display based on the 'box' URL parameter
$box = $_GET['box'] ?? 'inbox';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    
    <script src="../assets/js/admin.js"></script>
    
</head>
<body>




<!-- Unique Message Sidebar -->
<div class="message-sidebar">
        <div class="message-sidebar-header">
            <h2>Messages</h2> <!-- Sidebar Header -->
        </div>
        <div class="message-sidebar-nav">
        <a href="../includes/compose.php">Compose </a>
        <a href="../includes/sent.php">Sent </a><i class="fas fa-inbox"></i> 
        <a href="../includes/inbox.php">Inbox</a><i class="fas fa-paper-plane"></i>
        <a href="../includes/AllMessages.php">All Messages</a><i class="fas fa-paper-plane"></i> 
        </div>
    </div>
</div>

<!-- Inside your #main-content where the welcome message is displayed -->
<div class="welcome-section">
    <img src="../assets/images/img.jpg" alt="Welcome to Messages" class="welcome-image">
    <h1>Welcome to Your Messaging Dashboard</h1>
    <p>Manage your messages, check your inbox, and stay connected!</p>
    <!-- Quick stats can be dynamically generated using PHP to pull real data -->
    <div class="quick-stats">
        <!--<span>You have <strong>?></strong> unread messages.</span>-->
</div>
</div>

   
    

</body>
</html>