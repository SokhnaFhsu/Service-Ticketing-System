<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include 'db.php';

error_log("Received request: " . file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    error_log("Action: " . $data['action']); // Confirm action received

    if ($data['action'] == 'callNext') {
        $sql = "UPDATE tickets SET status = 'Now Serving' WHERE status = 'Queued' ORDER BY IssueTime ASC LIMIT 1";
        if ($conn->query($sql) && $conn->affected_rows > 0) {
            $newTicketId = $conn->insert_id;
            echo json_encode(['success' => true, 'ticketId' => $newTicketId]);
        } else {
            echo json_encode(['error' => 'No queued tickets available']);
        }
    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

$conn->close();
?>
