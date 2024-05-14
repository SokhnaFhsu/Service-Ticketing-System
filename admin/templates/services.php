<?php
session_start();
include '../includes/db.php';
include '../includes/sidebar.php';

// Redirect if user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../modules/login.php');
    exit;
}

// Fetch existing services
$stmtServices = $pdo->query("SELECT * FROM services");
$services = $stmtServices->fetchAll(PDO::FETCH_ASSOC);

// Handle POST requests for updates, adds, and deletes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updateService'])) {
        // Updating an existing service
        $serviceId = $_POST['serviceId'];
        $serviceName = $_POST['serviceName'];
        $serviceDescription = $_POST['serviceDescription'];

        $stmtUpdateService = $pdo->prepare("UPDATE services SET ServiceName = ?, Description = ? WHERE ServiceID = ?");
        $stmtUpdateService->execute([$serviceName, $serviceDescription, $serviceId]);
        header('Location: services.php');
        exit;
    } elseif (isset($_POST['addService'])) {
        // Adding a new service
        $serviceName = $_POST['serviceName'];
        $serviceDescription = $_POST['serviceDescription'];

        $stmtInsertService = $pdo->prepare("INSERT INTO services (ServiceName, Description) VALUES (?, ?)");
        $stmtInsertService->execute([$serviceName, $serviceDescription]);
        header('Location: services.php');
        exit;
    } elseif (isset($_POST['deleteService'])) {
        // Deleting a service
        $serviceId = $_POST['serviceId'];

        $stmtDeleteService = $pdo->prepare("DELETE FROM services WHERE ServiceID = ?");
        $stmtDeleteService->execute([$serviceId]);
        header('Location: services.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Management</title>
    <link href="../assets/css/employee.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="main-content">
    <h2>Manage Services</h2>

    <!-- Display existing services -->
    <h2>Services</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
            <tr>
                <td><?= $service['ServiceID'] ?></td>
                <td contenteditable="false"><?= htmlspecialchars($service['ServiceName']) ?></td>
                <td contenteditable="false"><?= htmlspecialchars($service['Description']) ?></td>
                <td>
                    <button onclick="editService(this)">Edit</button>
                    <button onclick="saveService(this, <?= $service['ServiceID'] ?>)" style="display:none;">Save</button>
                    <form action="" method="post" style="display: inline;">
                        <input type="hidden" name="serviceId" value="<?= $service['ServiceID'] ?>">
                        <button type="submit" name="deleteService">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        function editService(button) {
            var tr = $(button).closest('tr');
            tr.find('td[contenteditable]').prop('contenteditable', true);
            $(button).hide();
            tr.find('button:contains("Save")').show();
        }

        function saveService(button, serviceId) {
            var tr = $(button).closest('tr');
            var serviceName = tr.find('td:eq(1)').text();
            var serviceDescription = tr.find('td:eq(2)').text();

            $.ajax({
                url: 'services.php',  // Submit form to the same PHP script
                type: 'POST',
                data: {
                    updateService: 1,  // Ensure this key matches the check in the PHP block
                    serviceId: serviceId,
                    serviceName: serviceName,
                    serviceDescription: serviceDescription
                },
                success: function(response) {
                    alert('Service updated successfully');
                    window.location.reload();  // Reload the page to reflect changes
                },
                error: function() {
                    alert('Error updating service');
                }
            });
        }
    </script>

    <!-- Form to add new service -->
   <br>
    <form id = "fill" action="" method="post">
    <h3>Add New Service</h3>
        <label for="serviceName">Name:</label>
        <input type="text" id="serviceName" name="serviceName" required>
        <label for="serviceDescription">Description:</label>
        <input type="text" id="serviceDescription" name="serviceDescription">
        <button type="submit" name="addService">Add Service</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="../staff/staff.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
