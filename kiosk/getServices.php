
<?php
include '../admin/includes/db.php';

header('Content-Type: application/json');

$categoryId = isset($_GET['categoryId']) ? intval($_GET['categoryId']) : 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE CategoryID = ?");
    $stmt->execute([$categoryId]);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($services);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}


?>
