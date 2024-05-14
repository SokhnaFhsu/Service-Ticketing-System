<?php
session_start();

include '../includes/db.php';
include '../includes/sidebar.php';
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

$stmt = $pdo->prepare("SELECT TicketID, CustomerID , ServiceName, IssueTime, Status FROM tickets WHERE CounterID = ? ORDER BY IssueTime DESC LIMIT 5");
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
$stmt = $pdo->prepare("SELECT TicketID, CustomerID, ServiceName, CounterID, status, IssueTime FROM tickets ORDER BY IssueTime DESC");
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createTicket'])) {
    $categoryName = htmlspecialchars($_POST['categoryName']);
    $serviceName = htmlspecialchars($_POST['serviceName']);

    // Check if the customer exists in the database
    $customerID = 1; // Replace this with the actual customer ID or fetch it from the database
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM customer WHERE CustomerID = ?");
    $stmt->execute([$customerID]);
    $customerExists = $stmt->fetchColumn();

    if ($customerExists) {
        // Insert the ticket into the database
        $stmt = $pdo->prepare("INSERT INTO tickets (CustomerID, ServiceID) VALUES (?, ?)");
        $stmt->execute([$customerID, $serviceName]);

        // Redirect to the tickets page or display a success message
        header('Location: ticket.php');
        exit;
    } else {
        // Customer does not exist, handle the error accordingly
        echo "Customer does not exist.";
    }


    // Redirect to the tickets page or display a success message
    header('Location: ticket.php');
    exit;

    // Check if the form is submitted


}


?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="../assets/css/tickets.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<header>


    <div class="nav-right">
        <!-- Right-aligned action items -->
        <!-- Right-aligned action items with icons -->
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
</header>


<div class="main-content">
    <div class="top-bar">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h2>Tickets</h2>
                </div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTicketModal">
            Create
        </button>
                
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
                            <th scope="col">Customer ID</th>
                            <th scope="col">Service </th>
                            
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
                <td><?= htmlspecialchars($ticket['ServiceName']) ?></td>
               
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
                <button onclick="editCategory(this)">Edit</button>
                    <button onclick="saveCategory(this, <?php echo $category['CategoryID']; ?>)" style="display:none;">Save</button>
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

<div class="modal fade" id="createTicketModal" tabindex="-1" role="dialog" aria-labelledby="createTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <div class="create-ticket-form">
    <h2>Create Ticket</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="categoryName">Category Name:</label>
            <select name="categoryName" id="categoryName" class="form-control">
                <?php
                // Fetch category names from the database
                $stmt = $pdo->query("SELECT categoryName FROM categories");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['categoryName'] . "'>" . $row['categoryName'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="serviceName">Service Name:</label>
            <select name="serviceName" id="serviceName" class="form-control">
                <?php
                // Fetch service names from the database
                $stmt = $pdo->query("SELECT serviceName FROM services");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['serviceName'] . "'>" . $row['serviceName'] . "</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" name="createTicket" class="btn btn-primary">Create Ticket</button>
    </form>
</div>

<script>
        function editCategory(button) {
            var tr = $(button).closest('tr');
            tr.find('td[contenteditable]').prop('contenteditable', true);
            $(button).hide();
            tr.find('button:contains("Save")').show();
        }

        function saveCategory(button, categoryId) {
            var tr = $(button).closest('tr');
            var categoryName = tr.find('td:eq(1)').text();
            var categoryDescription = tr.find('td:eq(2)').text();

            $.ajax({
                url: 'categories.php', // Submit form to the same PHP script
                type: 'POST',
                data: {
                    update: 1, // Key for updating
                    id: categoryId,
                    name: categoryName,
                    description: categoryDescription
                },
                success: function(response) {
                    alert('Category updated successfully');
                    window.location.reload(); // Reload the page to reflect changes
                },
                error: function() {
                    alert('Error updating category');
                }
            });
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="../staff/staff.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
