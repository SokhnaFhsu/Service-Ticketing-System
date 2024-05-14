<?php
// Assuming you have a PDO connection instance in $pdo
require '../includes/db.php'; // Your PDO database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id']; // EmployeeID
    $column = $_POST['column']; // Which column to update: 'Name', 'Email', or 'Role'
    $value = $_POST['value']; // New value for the column

    // Security Note: It's essential to validate and sanitize these inputs properly.
    // Additionally, dynamically using column names in SQL statements can be risky and should be handled carefully.

    // Whitelist column names to prevent SQL injection
    $allowedColumns = ['Name', 'Email', 'Role'];
    if (!in_array($column, $allowedColumns)) {
        echo 'Invalid column';
        exit;
    }

    $sql = "UPDATE employees SET $column = ? WHERE EmployeeID = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$value, $id])) {
        echo 'Success';
    } else {
        echo 'Error';
    }
}
?>
