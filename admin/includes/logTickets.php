<?php
include 'db.php';  // Ensure this includes your PDO connection setup

$customerID = $_POST['customerID'] ?? 0;  // Default or from session/user context
$serviceName = $_POST['serviceName'] ?? 'Unknown';
$status = $_POST['status'] ?? 'Open';  // Default status to 'Open'

// Prepare the SQL query
$sql = "INSERT INTO tickets (CustomerID, ServiceName, Status) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);

// Execute the query with the collected data
try {
    $stmt->execute([$customerID, $serviceName, $status]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
