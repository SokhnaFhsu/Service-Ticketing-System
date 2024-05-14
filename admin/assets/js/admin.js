"use strict";

$(document).ready(function() {
    // Handling click event for "Compose" link
    $('#compose-link').click(function(e) {
        e.preventDefault(); // Prevent default link behavior
        // Show the compose form
        $('#compose-section').show();
    });

    // Assuming you want to hide the compose form when other links are clicked
    $('.sidebar-link').click(function(e) {
        e.preventDefault(); // Prevent default link behavior
        
        // Extract 'box' data attribute
        var box = $(this).data('box');
        // Hide the compose form if other sidebar links are clicked
        if (box !== 'compose') {
            $('#compose-section').hide();
        }
    });
});


// Example JavaScript to handle ticket status updates using AJAX
function updateTicketStatus(ticketId, newStatus) {
    fetch('update_ticket_status.php', {
        method: 'POST',
        body: JSON.stringify({ ticketId: ticketId, status: newStatus }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Ticket status updated!');
            // Reload the part of the page that contains the ticket information, or update the DOM directly
        } else {
            alert('Failed to update ticket status.');
        }
    })
    .catch(error => console.error('Error:', error));
}

function makeEditable(element) {
    var currentHtml = $(element).html();
    var inputField = '<input type="text" class="edit-input" value="' + currentHtml + '" onblur="saveChanges(this, \'' + element.getAttribute('data-column') + '\', \'' + element.getAttribute('data-id') + '\')">';
    $(element).html(inputField);
    $(element).find('input').focus();
}

function saveChanges(element, column, id) {
    var newValue = $(element).val();
    $.ajax({
        url: 'update_employee.php', // URL to server-side PHP script
        type: 'POST',
        data: {
            id: id,
            column: column,
            value: newValue
        },
        success: function(response) {
            $(element).parent().html(newValue);
            // Optional: Add success feedback
        },
        error: function() {
            // Optional: Add error handling
            alert("Update failed. Please try again.");
        }
    });
}

function removeEmployee(id) {
    // Optional: AJAX call to server-side PHP script for removing an employee
    // Here you'd also confirm the action and, on success, remove the row from the table
    if(confirm('Are you sure you want to remove this employee?')) {
        console.log('Remove employee logic here.'); // Placeholder for actual remove logic
    }

}

function fetchEmployees(searchTerm) {
    $.ajax({
        url: 'fetch_employees.php', // The PHP file that retrieves the employees based on the search term
        type: 'GET',
        data: {search: searchTerm},
        success: function(response) {
            // Update the table body with the returned HTML
            $('tbody').html(response);
        }
    });
}


$(document).ready(function() {
    $('#addEmployeeForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        $.ajax({
            type: 'POST',
            url: 'manage-employee.php', // Your PHP script to handle the insertion
            data: $(this).serialize(), // Serialize the form data
            success: function(response) {
                $('#message').text(response); // Display success message
                $('#addEmployeeForm').trigger("reset"); // Optional: reset form fields
            },
            error: function() {
                $('#message').text('An error occurred. Please try again.');
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Get all buttons with class 'remove-button'
    var removeButtons = document.querySelectorAll('.remove-button');
    
    // Loop through each button
    removeButtons.forEach(function(button) {
        // Add click event listener to each button
        button.addEventListener('click', function() {
            // Get the ticket ID from the data attribute
            var ticketID = button.getAttribute('data-ticket-id');
            
            // Send an AJAX request to remove_ticket.php
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '../modules/manage-tickets.php?id=' + ticketID, true);
            xhr.onload = function() {
                // Check if the request was successful
                if (xhr.status === 200) {
                    // Remove the table row if successful
                    button.closest('tr').remove();
                } else {
                    // Display an error message if the request failed
                    console.error('Failed to remove ticket');
                }
            };
            xhr.send();
        });
    });
});




