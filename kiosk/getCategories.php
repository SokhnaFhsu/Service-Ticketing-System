<?php
include '../admin/includes/db.php';  // Assuming you have a file that sets up the PDO connection

header('Content-Type: application/json');  // Set header for JSON response

try {
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categories);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
