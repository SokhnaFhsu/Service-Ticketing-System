<?php
session_start();

include '../admin/includes/db.php';

$latestTickets = [];

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../modules/login.php');
    exit;
}

$receiver_id = 1;
$loggedInUserId = $_SESSION['UserID'];
$stmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE status = 'unread' AND receiver_id = :receiver_id");
$stmt->bindParam(':receiver_id', $loggedInUserId, PDO::PARAM_INT);
$stmt->execute();
$unreadMessagesCount = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE status = 'unread' AND UserID = :UserID");
$stmt->bindParam(':UserID', $loggedInUserId, PDO::PARAM_INT);
$stmt->execute();
$unreadNotificationsCount = $stmt->fetchColumn();

$loggedInUserId = $_SESSION['UserID'];
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE status = 'open' AND CounterID = ?");
$stmt->bindParam(1, $loggedInUserId, PDO::PARAM_INT);
$stmt->execute();
$openTicketsCount = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT TicketID, CustomerID , ServiceID, IssueTime, Status FROM tickets WHERE CounterID = ? ORDER BY IssueTime DESC LIMIT 5");
$stmt->bindParam(1, $loggedInUserId, PDO::PARAM_INT);
$stmt->execute();
$latestTickets = $stmt->fetchAll();


if (isset($_POST['delete']) && isset($_POST['ticketId'])) {
    $stmt = $pdo->prepare("DELETE FROM tickets WHERE TicketID = ?");
    $stmt->execute([$_POST['ticketId']]);
    echo "<p>Ticket deleted successfully.</p>";
}

// Handling updates
if (isset($_POST['update']) && isset($_POST['ticketId']) && isset($_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE tickets SET Status = ? WHERE TicketID = ?");
    $stmt->execute([$_POST['status'], $_POST['ticketId']]);
    if ($stmt->rowCount() > 0) { // Check if any row was affected
        echo "<p>Ticket updated successfully.</p>";
    }
}

// Fetching tickets
$stmt = $pdo->prepare("SELECT TicketID, CustomerID, ServiceID, CounterID, status, IssueTime FROM tickets ORDER BY IssueTime DESC");
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="../staff/ticket.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<header>
    <nav class="navbar">
        <!-- Left-aligned navigation links -->
        <div class="nav-left">
        <div class="user-profile">
        <img src="path_to_profile_image.jpg" alt="User Name" class="profile-pic" />

    </div>
            <a href="../staff/staff.php">Dashboard</a>
            <a href="../staff/tickets.php">Tickets</a>
            <a href="../admin/templates/settings.php">Profile</a>
        </div>

        <!-- Right-aligned action items -->
        <!-- Right-aligned action items with icons -->
<div class="nav-right">
    <a href="../modules/notifications.php" class="icon-button">
        <i class="far fa-bell"></i>
        <?php if ($unreadNotificationsCount > 0): ?>
            <span class="unread-count"><?php echo $unreadNotificationsCount; ?></span>
        <?php endif; ?>
    </a>
    <a href="../modules/message.php" class="icon-button">
        <i class="far fa-envelope"></i>
        <?php if ($unreadMessagesCount > 0): ?>
            <span class="unread-count"><?php echo $unreadMessagesCount; ?></span>
        <?php endif; ?>
    </a>
    <a href="../modules/logout.php" class="icon-button">
        <i class="fas fa-sign-out-alt"></i>
    </a>
</div>

    </nav>
</header>

<div class="main-content">
    <div class="top-bar">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h2>Tickets</h2>
                </div>
                <div class="col text-right">
                <button class="create-button" type="button" onclick="toggleCreateBox()">Create <i class="fas fa-plus"></i></button>
                </div>
            </div>
            <!-- Create box -->
            <div class="create-box" id="createBox">
                <!-- Input fields go here -->
                <input type="text" placeholder="Customer Name">
                <input type="text" placeholder="Service Type">
                <button onclick="submitTicket()">Submit</button>
            </div>
        </div>
    </div>
</div>

        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-12">
                <div class="wrap">
   <div class="search">
      <input type="text" class="searchTerm" placeholder="search ticket?">
      <button type="submit" class="searchButton">
        <i class="fa fa-search"></i>
     </button>
   </div>
</div>
<br>
                </div>
                <table id="tickets-table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Ticket ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Service Type</th>
                            <th scope="col">Counter</th>
                            <th scope="col">Status</th>
                            <th scope="col">Issue Time</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?= htmlspecialchars($ticket['TicketID']) ?></td>
                <td><?= htmlspecialchars($ticket['CustomerID']) ?></td>
                <td><?= htmlspecialchars($ticket['ServiceID']) ?></td>
                <td><?= htmlspecialchars($ticket['CounterID']) ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="ticketId" value="<?= $ticket['TicketID'] ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option <?= $ticket['status'] === 'Open' ? 'selected' : '' ?>>Open</option>
                            <option <?= $ticket['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
                            <option <?= $ticket['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                        </select>
                        <input type="hidden" name="update" value="1">
                    </form>
                </td>
                <td><?= htmlspecialchars($ticket['IssueTime']) ?></td>
                <td>
                    <form action="" method="post">
                    
                    <form method="POST" action="categories.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $category['CategoryID']; ?>">
                        <button type="submit" name="delete">Delete</button>
            </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
