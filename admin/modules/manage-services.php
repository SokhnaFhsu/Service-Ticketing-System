<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'db.php'; // Your database connection

    $service_name = $_POST['ServiceName'];
    $description = $_POST['Description'];

    $query = "INSERT INTO services (ServiceName, Description) VALUES (?, ?)";
    $stmt = $pdo->prepare($query);

    if ($stmt->execute([$service_name, $description])) {
        echo "Service added successfully.";
    } else {
        echo "Error adding service.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Services</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="styles.css"> <!-- Your CSS file -->
</head>
<body>

<div class="container">
    <?php include '../includes/sidebar.php'; ?> <!-- Your sidebar navigation -->

    <div id="main-content">
        <h1>Manage Services</h1>

        <!-- Form to add/edit services -->
        
        <!-- Services table -->
        <table id="services-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Services will be listed here -->
            </tbody>
        </table>


        <div class="service-form">
            <form action="service_actions.php" method="POST">
                <input type="hidden" name="service_id" value=""> <!-- For edits -->
                <div class="form-group">
                    <label for="name">Service Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                <button type="submit" name="action" value="add">Add Service</button>
            </form>
        </div>

    </div>
</div>

</body>
</html>
