document.addEventListener('DOMContentLoaded', function() {
    const callNextButton = document.getElementById('callNextButton');
    const messageDisplay = document.getElementById('message');

    if (!callNextButton) {
        console.error('Call Next Customer button not found');
        return; // Stop further execution if button is not found
    }

    callNextButton.addEventListener('click', function() {
        console.log('Button clicked'); // Check if this logs when the button is clicked
        fetch('http://localhost/SERVICE%20TICKETING%20SYSTEM/admin/includes/fetchNextTicket.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action: 'callNext' })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response received', data); // Check what the server responds with
            if (data.success) {
                messageDisplay.textContent = "Next customer called successfully: Ticket " + data.ticketId;
            } else {
                messageDisplay.textContent = "Failed to call the next customer: " + data.message;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageDisplay.textContent = 'Failed to call the next ticket due to an error.';
        });
    });
});
