<?php
session_start();
include '../includes/db.php';
include '../includes/sidebar.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch categories
function fetchCategories($pdo) {
    $stmt = $pdo->query("SELECT * FROM categories");
    return $stmt->fetchAll();
}

$categories = fetchCategories($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO categories (CategoryName, Description) VALUES (:name, :description)");
        $stmt->execute(['name' => $_POST['name'], 'description' => $_POST['description']]);
    } elseif (isset($_POST['update'])) {
        $stmt = $pdo->prepare("UPDATE categories SET CategoryName = :name, Description = :description WHERE CategoryID = :id");
        $stmt->execute(['name' => $_POST['name'], 'description' => $_POST['description'], 'id' => $_POST['id']]);
    } elseif (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE CategoryID = :id");
        $stmt->execute(['id' => $_POST['id']]);
    }
    header("Location: categories.php");  // Reload to see changes
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link href="../assets/css/employee.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h2>Manage Categories</h2>
    

    
    <table border="1">
        <thead>
            <tr>
                <th>Category ID</th>
                <th>Category Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category['CategoryID']; ?></td>
                <td contenteditable="false"><?php echo htmlspecialchars($category['CategoryName']); ?></td>
                <td contenteditable="false"><?php echo htmlspecialchars($category['Description']); ?></td>
                <td>
                    <button onclick="editCategory(this)">Edit</button>
                    <button onclick="saveCategory(this, <?php echo $category['CategoryID']; ?>)" style="display:none;">Save</button>
                    <form method="POST" action="categories.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $category['CategoryID']; ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br>
    
    <form id ="fill" action="categories.php" method="post">
        <h2>Add a Category<h2>
        <input type="hidden" name="id" value="">
        <input type="text" name="name" placeholder="Category Name" required>
        <textarea name="description" placeholder="Description"></textarea>
        <button type="submit" name="add">Add Category</button>
    </form>


    <script>
        function editCategory(button) {
            var tr = $(button).closest('tr');
            tr.find('td[contenteditable]').prop('contenteditable', true);
            $(button).hide();
            tr.find('button:contains("Save")').show();
        }

        function saveCategory(button, categoryId) {
            var tr = $(button).closest('tr');
            var categoryName = tr.find('td:eq(1)').text();
            var categoryDescription = tr.find('td:eq(2)').text();

            $.ajax({
                url: 'categories.php', // Submit form to the same PHP script
                type: 'POST',
                data: {
                    update: 1, // Key for updating
                    id: categoryId,
                    name: categoryName,
                    description: categoryDescription
                },
                success: function(response) {
                    alert('Category updated successfully');
                    window.location.reload(); // Reload the page to reflect changes
                },
                error: function() {
                    alert('Error updating category');
                }
            });
        }
    </script>
</body>
</html>
