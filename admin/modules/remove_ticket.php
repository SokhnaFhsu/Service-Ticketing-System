<?php
session_start();
require_once '../includes/db.php'; // Ensure your DB connection settings are correct

if (!isset($_SESSION['UserID'])) {
    // User not logged in
    echo json_encode(['success' => false]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ticketId'])) {
    $ticketId = $_POST['ticketId'];

    try {
        $stmt = $pdo->prepare("DELETE FROM tickets WHERE TicketID = ?");
        $stmt->bindParam(1, $ticketId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
