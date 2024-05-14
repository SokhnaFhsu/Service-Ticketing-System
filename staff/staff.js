document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('searchInput');
    if(input) {
        input.addEventListener('keyup', function(event) {
            // Check if "Enter" key was pressed
            if (event.key === "Enter") {
                searchTickets();  // Execute search function
            }
        });
    }
});

function searchTickets() {
    var input = document.getElementById("searchInput");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("tickets-table");
    var tr = table.getElementsByTagName("tr");

    for (var i = 0; i < tr.length; i++) {
        var td = tr[i].getElementsByTagName("td");
        var showRow = false;
        for (var j = 0; j < td.length; j++) {
            if (td[j]) {
                var txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    showRow = true;
                    break;
                }
            }
        }
        tr[i].style.display = showRow ? "" : "none";
    }
}

$(document).ready(function() {
    $('#createTicketForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: 'createTickets.php',
            data: formData,
            success: function(response) {
                $('#message').text(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
});
function toggleCreateBox() {
    var createBox = document.getElementById("createBox");
    if (createBox.style.display === "none") {
        createBox.style.display = "block";
    } else {
        createBox.style.display = "none";
    }
}

function submitTicket() {
    // Add logic to handle form submission here
    // This function will be called when the "Submit" button inside the create box is clicked
}
