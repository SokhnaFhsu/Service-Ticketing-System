<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start(); 
 
    
// Ensure you have the correct path to your database connection file
require_once 'db.php';

// Check connection (assuming $conn is your database connection variable)
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$action = $_GET['action'] ?? '';

if ($action == 'fetchCategories') {
    $sql = "SELECT * FROM categories";
    $result = $conn->query($sql);

    $categories = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = ['id' => $row['CategoryID'], 'name' => $row['CategoryName']];
        }
        echo json_encode($categories);
    } else {
        echo json_encode(['error' => 'No categories found or query failed']);
    }
} 

elseif ($action == 'fetchServices' && isset($_GET['categoryId'])) {
    $categoryId = $_GET['categoryId'];
    $stmt = $conn->prepare("SELECT * FROM services WHERE CategoryID = ?");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    $services = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $services[] = ['id' => $row['ServiceID'], 'name' => $row['ServiceName']];
        }
        ob_clean(); // Clear the buffer here before echoing the JSON output.
        echo json_encode($services);
    } else {
        echo json_encode(['error' => 'No services found or query failed']);
    }
} else {
    echo json_encode(['error' => 'Invalid action']);
}

// Close the connection if it's still open.
if ($conn) {
    $conn->close();
}

// End and clean up the buffer.
ob_end_flush();
?>