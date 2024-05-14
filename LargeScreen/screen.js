document.addEventListener('DOMContentLoaded', function() {
    const displayArea = document.getElementById('now-serving-table').getElementsByTagName('tbody')[0];

    function updateTicketDisplay() {
        fetch('http://localhost/SERVICE%20TICKETING%20SYSTEM/admin/includes/fetchTickets.php')  // Adjust the path as necessary
            .then(response => response.json())
            .then(tickets => {
                displayArea.innerHTML = ''; // Clear previous entries
                tickets.forEach(ticket => {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td>${ticket.TicketID}</td><td>${ticket.CounterID}</td>`;
                    displayArea.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Failed to fetch tickets:', error);
                displayArea.innerHTML = '<tr><td colspan="3">Error loading data</td></tr>';
            });
    }

    // Update display immediately and then every 30 seconds
    updateTicketDisplay();
    setInterval(updateTicketDisplay, 30000);
});

