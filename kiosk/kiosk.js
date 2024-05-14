document.addEventListener('DOMContentLoaded', function() {
    const startButton = document.getElementById('startButton');
    if (startButton) {
        startButton.addEventListener('click', getCategories);
    }
    updateDateTime();
    setInterval(updateDateTime, 1000); // Update the time every second
});

function updateDateTime() {
    const now = new Date();
    const dateTimeFormat = new Intl.DateTimeFormat('en', { 
        year: 'numeric', month: 'long', day: '2-digit', 
        hour: '2-digit', minute: '2-digit', second: '2-digit' 
    });
    document.getElementById('dateTime').textContent = dateTimeFormat.format(now);
}

function startOver() {
    document.getElementById('categories').style.display = 'none';
    document.getElementById('services').style.display = 'none';
    document.getElementById('printButton').style.display = 'none';
    document.getElementById('feedback').style.display = 'none';
    document.getElementById('startButton').style.display = 'block';
}

function submitFeedback(feedback) {
    console.log('Feedback submitted:', feedback); // Placeholder for actual submission logic
    alert(`Thank you for your feedback! You felt: ${feedback}`);
    startOver(); // Optionally reset the kiosk to start state
}


function getCategories() {
    fetch('getCategories.php')
        .then(response => response.json())
        .then(categories => {
            // Handle category display
        })
        .catch(error => console.error('Failed to load categories:', error));
}

function submitFeedback(feedback) {
    console.log('Feedback submitted:', feedback); // Placeholder for actual submission logic
    alert("Thank you for your feedback!");
    document.getElementById('feedback').style.display = 'none';
    restart(); // Optionally restart after feedback
}

function restart() {
    document.getElementById('categories').style.display = 'none';
    document.getElementById('services').style.display = 'none';
    document.getElementById('printButton').style.display = 'none';
    document.getElementById('feedback').style.display = 'none';
    document.getElementById('startButton').style.display = 'block';
    document.getElementById('restartButton').style.display = 'none'; // Hide the Start Over button
}

function displayCurrentDateTime() {
    const now = new Date();
    const dateTimeFormat = new Intl.DateTimeFormat('en', { dateStyle: 'full', timeStyle: 'medium' });
    document.getElementById('currentDateTime').innerText = dateTimeFormat.format(now);
}

setInterval(displayCurrentDateTime, 1000); // Update date and time every second


function getCategories() {
    fetch('getCategories.php')
        .then(response => response.json())
        .then(categories => {
            const categoriesDiv = document.getElementById('categories');
            let html = '<h2>Please select a category:</h2>';
            categories.forEach(category => {
                html += `<button onclick="getServices(${category.CategoryID})">${category.CategoryName}</button>`;
            });
            categoriesDiv.innerHTML = html;
            categoriesDiv.style.display = 'block'; // Show categories
        })
        .catch(error => {
            console.error('Failed to load categories:', error);
        });
}

function getServices(categoryId) {
    fetch(`getServices.php?categoryId=${categoryId}`)
        .then(response => response.json())
        .then(services => {
            const servicesDiv = document.getElementById('services');
            let html = '<h2>Please select a service:</h2>';
            services.forEach(service => {
                html += `<button onclick="printTicket('${service.ServiceName}', '${service.Description}')">${service.ServiceName}</button>`;
            });
            servicesDiv.innerHTML = html;
            servicesDiv.style.display = 'block'; // Show services
            document.getElementById('categories').style.display = 'none'; // Hide categories
        })
        .catch(error => {
            console.error('Failed to load services:', error);
        });
}

function printTicket(serviceName, description) {
    const printButtonDiv = document.getElementById('printButton');
    printButtonDiv.innerHTML = `<h2>Your Ticket:</h2><p>Service: ${serviceName}<br>Description: ${description}</p><button onclick="simulatePrinting()">Print Your Ticket</button>`;
    printButtonDiv.style.display = 'block'; // Show print options
    document.getElementById('services').style.display = 'none'; // Hide services
}

function simulatePrinting() {
    const printButtonDiv = document.getElementById('printButton');
    printButtonDiv.innerHTML = "<p>Printing your ticket...</p>"; // Show printing message
    setTimeout(showFeedbackForm, 3000); // Wait for 3 seconds before showing feedback form
}

function showFeedbackForm() {
    document.getElementById('printButton').style.display = 'none'; // Hide print options after "printing"
    document.getElementById('feedback').style.display = 'block'; // Show feedback form
}

