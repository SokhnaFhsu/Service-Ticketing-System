<?php
session_start();
include '../includes/db.php';
include '../includes/sidebar.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch employees
function fetchEmployees($pdo) {
    $stmt = $pdo->query("SELECT * FROM employees");
    return $stmt->fetchAll();
}

$employees = fetchEmployees($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO employees (Name, Role) VALUES (:name, :department)");
        $stmt->execute(['name' => $_POST['name'], 'department' => $_POST['department']]);
    } elseif (isset($_POST['update'])) {
        $stmt = $pdo->prepare("UPDATE employees SET EmployeeName = :name, Department = :department WHERE EmployeeID = :id");
        $stmt->execute(['name' => $_POST['name'], 'department' => $_POST['department'], 'id' => $_POST['id']]);
    } elseif (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM employees WHERE EmployeeID = :id");
        $stmt->execute(['id' => $_POST['id']]);
    }
    header("Location: employees.php");  // Reload to see changes
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <link href="../assets/css/employee.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
    
<h2>Manage Employees</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
            <tr>
                <td><?php echo $employee['EmployeeID']; ?></td>
                <td contenteditable="false"><?php echo htmlspecialchars($employee['Name']); ?></td>
                <td contenteditable="false"><?php echo htmlspecialchars($employee['Role']); ?></td>
                <td>
                    <button onclick="editEmployee(this)">Edit</button>
                    <button onclick="saveEmployee(this, <?php echo $employee['EmployeeID']; ?>)" style="display:none;">Save</button>
                    <form method="POST" action="employees.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $employee['EmployeeID']; ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<br>
    <form id = "fill" action="employees.php" method="post">
    <h3>Employees Management</h3>
        <input type="hidden" name="id" value="">
        <input type="text" name="name" placeholder="Employee Name" required>
        <input type="text" name="department" placeholder="Department" required>
        <button type="submit" name="add">Add Employee</button>
    </form>

    <script>
        function editEmployee(button) {
            var tr = $(button).closest('tr');
            tr.find('td[contenteditable]').prop('contenteditable', true);
            $(button).hide();
            tr.find('button:contains("Save")').show();
        }

        function saveEmployee(button, employeeId) {
            var tr = $(button).closest('tr');
            var employeeName = tr.find('td:eq(1)').text();
            var department = tr.find('td:eq(2)').text();

            $.ajax({
                url: 'employees.php', // Submit form to the same PHP script
                type: 'POST',
                data: {
                    update: 1, // Key for updating
                    id: employeeId,
                    name: employeeName,
                    department: department
                },
                success: function(response) {
                    alert('Employee updated successfully');
                    window.location.reload(); // Reload the page to reflect changes
                },
                error: function() {
                    alert('Error updating employee');
                }
            });
        }
    </script>
</body>
</html>
