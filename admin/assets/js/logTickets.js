function simulatePrinting(serviceName, serviceId) {
    // We prepare data to send to the server
    const data = {
        serviceName: serviceName,
        serviceId: serviceId,
        customerID: 1,  // Assuming a default customer ID for simplicity
        counterNumber: 1,  // Assuming a default counter number
        categoryName: 'General'  // Assuming a default category name
    };

    // Sending a POST request to logTicket.php
    fetch('logTickets.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: Object.keys(data).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key])).join('&')
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log("Ticket logged successfully.");
            setTimeout(showFeedbackForm, 3000); // Wait 3 seconds then show feedback
        } else {
            console.error('Failed to log ticket:', data.error);
        }
    })
    .catch(error => {
        console.error('Error logging ticket:', error);
    });
}
function simulatePrinting(serviceName, serviceId) {
    console.log('Service ID:', serviceId);  // This will output the Service ID to the console
    // Remaining code...
}
