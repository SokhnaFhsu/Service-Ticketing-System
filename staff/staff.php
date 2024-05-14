<?php
session_start();


include '../admin/includes/db.php';
$latestTickets = [];
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: modules/login.php');
    exit;
}
// Fetch Unread Messages Count
$receiver_id = 1; // Example receiver ID, replace with the actual ID or variable
$loggedInUserId = $_SESSION['UserID']; // Example, adjust according to your session variable
$stmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE status = 'unread' AND receiver_id = :receiver_id");
$stmt->bindParam(':receiver_id', $loggedInUserId, PDO::PARAM_INT);
$stmt->execute();
$unreadMessagesCount = $stmt->fetchColumn();


$loggedInUserId = $_SESSION['UserID']; // This assumes you store the user's ID in the session

$stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE status = 'unread' AND UserID = :UserID");
$stmt->bindParam(':UserID', $loggedInUserId, PDO::PARAM_INT);
$stmt->execute();
$unreadNotificationsCount = $stmt->fetchColumn();


// Fetch count of open tickets assigned to the logged-in user (staff)
$loggedInUserId = $_SESSION['UserID'];
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE status = 'open' AND CounterID = ?");
$stmt->bindParam(1, $loggedInUserId, PDO::PARAM_INT);
$stmt->execute();
$openTicketsCount = $stmt->fetchColumn();

// Fetch the latest tickets associated with the logged-in user's counter
$stmt = $pdo->prepare("SELECT TicketID, CustomerID , ServiceID, IssueTime, Status FROM tickets WHERE CounterID = ? ORDER BY IssueTime DESC LIMIT 5");
$stmt->bindParam(1, $loggedInUserId, PDO::PARAM_INT);
$stmt->execute();
$latestTickets = $stmt->fetchAll();

// Fetch count of open tickets
$stmt = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'open'");
$openTicketsCount = $stmt->fetchColumn();

// Fetch count of tickets in progress
$stmt = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'in progress'");
$ticketsInProgressCount = $stmt->fetchColumn();

// Fetch count of closed tickets
$stmt = $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'closed'");
$closedTicketsCount = $stmt->fetchColumn();


$stmt = $pdo->query("SELECT TicketID, CustomerID , ServiceID, IssueTime, Status FROM tickets ORDER BY IssueTime DESC LIMIT 5");
$latestTickets = $stmt->fetchAll();


?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link href="../staff/staff.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../staff/assets/js/staff.js"></script>





<body>
<header>
    <nav class="navbar">
        <!-- Left-aligned navigation links -->
        <div class="nav-left">
        <div class="user-profile">
        <img src="prof.png" alt="User Name" class="profile-pic" />

    </div>
            <a href="../staff/staff.php">Dashboard</a>
            <a href="../staff/ticket.php">Tickets</a>
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

<div class="content-container">
    <h1>Dashboard</h1>

    <div class="dashboard-chart-row">
    <div class="dashboard-widgets">
        <div class="row">
            <div class="col">
            
                <div class="widget">
                <span class="widget-description">Open Tickets</span>
                    <span class="widget-number"><?php echo htmlspecialchars($openTicketsCount); ?></span>
                    
                    
                </div>
            </div>
            <div class="col">
                <!-- Widget for Tickets in Progress -->
                <div class="widget">
                <span class="widget-description">Tickets in Progress</span>
                    <span class="widget-number"><?php echo htmlspecialchars($ticketsInProgressCount); ?></span>
                    
                </div>
            </div>
            <div class="col">
                <!-- Widget for Closed Tickets -->
                <div class="widget">

                    <span class="widget-description">Closed Tickets</span>
                    <span class="widget-number"><?php echo htmlspecialchars($closedTicketsCount); ?></span>
                </div>
            </div>
            <!-- ... Other widgets as needed ... -->
        </div>
    </div>
    
    <div class="chart-container">
    <canvas id="myChart" width="400" height="200"></canvas>
</div>
        </div>
    <!-- Data Tables for Quick Overview -->
    <div class="data-tables">
        <!-- Table for Latest Tickets -->
        <div class="latestTickets">
            <h2>Latest Tickets</h2>
            <table>
                <!-- Table Headers -->
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Customer ID</th>
                        <th>Service ID</th>
                        <th>Issue Time </th>
                        <th>Status </th>
                        <!-- Other columns as needed -->
                    </tr>
                </thead>
                <?php if (!empty($latestTickets)): ?>
                <tbody>
                <?php foreach ($latestTickets as $ticket): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ticket['TicketID']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['CustomerID']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['ServiceID']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['IssueTime']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['Status']); ?></td>
                </tr>
            <?php endforeach; ?>
                </tbody>
                <?php else: ?>
                    <tbody>
                <tr><td colspan="4">No recent tickets found.</td></tr>
            </tbody>
        <?php endif; ?>
            </table>
        </div>
        <!-- ... Other tables as needed ... -->
    </div>

    <!-- Analytics Charts Section -->
    
    

<?php
// Include the footer
include '../admin/includes/footer.php';
?>

<script>
    // Fetch ticket data from PHP backend and store it in JavaScript array
    var ticketData = [
        { date: '2023-03-25', open: 0, inProgress: 1, closed: 0 },
        { date: '2023-03-26', open: 1, inProgress: 0, closed: 0 }
        // Add more data here as needed
    ];

    // Extract dates and ticket counts for each status
    var dates = ticketData.map(data => data.date);
    var openTickets = ticketData.map(data => data.open);
    var inProgressTickets = ticketData.map(data => data.inProgress);
    var closedTickets = ticketData.map(data => data.closed);

    // Render chart using Chart.js
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // Use bar chart type
        data: {
            labels: dates,
            datasets: [{
                label: 'Open Tickets',
                data: openTickets,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }, {
                label: 'Tickets in Progress',
                data: inProgressTickets,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: 'Closed Tickets',
                data: closedTickets,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>