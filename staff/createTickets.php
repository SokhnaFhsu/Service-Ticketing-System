<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../staff/staff.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../staff/staff.js"></script>
</head>
<body>
<div class="container">
    <h2>Create New Ticket</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="CustomerID">Customer ID:</label>
            <input type="number" class="form-control" id="CustomerID" name="CustomerID" required>
        </div>
        <div class="form-group">
            <label for="ServiceID">Service ID:</label>
            <input type="number" class="form-control" id="ServiceID" name="ServiceID" required>
        </div>
        <div class="form-group">
            <label for="IssueTime">Issue Time:</label>
            <input type="datetime-local" class="form-control" id="IssueTime" name="IssueTime" required>
        </div>
        <div class="form-group">
            <label for="Status">Status:</label>
            <input type="text" class="form-control" id="Status" name="Status" required>
        </div>
        <div class="form-group">
            <label for="CounterNumber">Counter Number:</label>
            <input type="number" class="form-control" id="CounterNumber" name="CounterNumber" required>
        </div>
        <div class="form-group">
            <label for="CounterID">Counter ID:</label>
            <input type="number" class="form-control" id="CounterID" name="CounterID" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Ticket</button>
    </form>
    <div id="message"></div>
</div>
</body>
</html>
