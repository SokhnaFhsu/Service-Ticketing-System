<?php

header('Access-Control-Allow-Origin: *'); // Allow all domains for CORS
header('Access-Control-Allow-Methods: GET, POST'); // Allowed methods
header('Content-Type: application/json'); // Set the Content-Type to JSON

// Include your database connection details
include 'db.php';

$sql = "SELECT t.TicketID, t.IssueTime, t.status, s.ServiceName, c.CounterID 
        FROM tickets t
        JOIN services s ON t.ServiceID = s.ServiceID
        JOIN counters c ON t.CounterNumber = c.CounterID
        WHERE t.status IN ('Open', 'In Progress') 
        ORDER BY t.IssueTime ASC";
$result = $conn->query($sql);

$tickets = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
    echo json_encode($tickets);
} else {
    echo json_encode([]); 
}

$conn->close();
?>
