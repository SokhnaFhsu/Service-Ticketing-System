<?php
require '../includes/db.php'; // Database connection

// Handle search
$searchTerm = $_GET['search'] ?? ''; // Get the search term from the query parameters

try {
    if (!empty($searchTerm)) {
        // A search term was provided, so filter the employee list
        $sql = "SELECT EmployeeID, Name, Email, Role FROM employees WHERE Name LIKE :searchTerm OR Email LIKE :searchTerm ORDER BY EmployeeID ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['searchTerm' => "%$searchTerm%"]);
    } else {
        // No search term, so fetch all employees
        $sql = "SELECT EmployeeID, Name, Email, Role FROM employees ORDER BY EmployeeID ASC";
        $stmt = $pdo->query($sql);
    }
    $employees = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Could not fetch employees: " . $e->getMessage());
}

$sql = "SELECT EmployeeID, Name, Email, Role FROM employees WHERE Name LIKE :searchTerm OR Email LIKE :searchTerm ORDER BY EmployeeID ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['searchTerm' => "%$searchTerm%"]);
$employees = $stmt->fetchAll();
$stmt = $pdo->prepare("SELECT EmployeeID, Name, Email, Role FROM employees WHERE Name LIKE :searchTerm OR Email LIKE :searchTerm");
$stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);
$employees = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract and sanitize form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);
    // Assume ServiceID is determined or selected in the form. Example uses a fixed value.
    $serviceID = 1;

    // SQL to insert new employee
    $sql = "INSERT INTO employees (Name, Email, Role, ServiceID) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $role, $serviceID]);
    
    // Redirect to refresh and see the new entry in the list
    header('Location: manage_employees.php');
    exit;
}

if (isset($_POST['submit'])) {
    // Sanitize input data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);

    // Insert into database
    $sql = "INSERT INTO employees (Name, Email, Role) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$name, $email, $role])) {
        $message = "Employee Added Successfully!";
    } else {
        $message = "An error occurred. Please try again.";
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Employees</title>
    <link rel="stylesheet" href="../assets/css/employee.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../assets/js/admin.js"></script>
</head>
<body>
    <h1>Employee List</h1>
    <!-- Search Bar -->
    <form action="manage_employees.php" method="get">
    <input type="text" name="search" placeholder="Search employees..." onkeyup="fetchEmployees(this.value)">

    <button type="submit">Search</button>
</form>


    <!-- Employees Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                <td><?= htmlspecialchars($employee['EmployeeID']); ?></td>
                <td class="editable" data-column="Name" data-id="<?= $employee['EmployeeID']; ?>" onclick="makeEditable(this)"><?= htmlspecialchars($employee['Name']); ?></td>
                <td class="editable" data-column="Email" data-id="<?= $employee['EmployeeID']; ?>" onclick="makeEditable(this)"><?= htmlspecialchars($employee['Email']); ?></td>
                <td class="editable" data-column="Role" data-id="<?= $employee['EmployeeID']; ?>" onclick="makeEditable(this)"><?= htmlspecialchars($employee['Role']); ?></td>
                <td>
                    
                    <a href="delete_employee.php?id=<?= $employee['EmployeeID']; ?>" class="remove-link" onclick="return confirm('Are you sure you want to remove this employee?');">Remove</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Add New Employee</h2>
    <form id="addEmployeeForm" action="manage_employees.php" method="post">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="role" placeholder="Role" required>
    <button type="submit" name="submit">Add Employee</button>
</form>
<div id="message"></div>
<?php if (!empty($message)): ?>
    <div id="message"><?= htmlspecialchars($message); ?></div>
<?php endif; ?>


</form>
</body>
</html>
