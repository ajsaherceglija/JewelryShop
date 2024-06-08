<?php
global $conn;
session_start();

require '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['CRID'];
    $newName = strtoupper($_POST['c_name']);

    $sql = "UPDATE categories SET c_name = '$newName' WHERE CRID = '$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_page.php");
        exit();
    } else {
        echo "Error updating category: " . $conn->error;
    }
}
$category = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM categories WHERE CRID='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    } else {
        echo "Category not found.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Edit Category</h1>
<form action="" method="post">
    <label for="CID">Category ID:</label>
    <input type="text" id="CRID" name="CRID" value="<?php echo $category['CRID'] ?>" readonly><br>

    <label for="c_name">New Name:</label>
    <input type="text" id="c_name" name="c_name" value="<?php echo $category['c_name'] ?>"><br>

    <button type="submit">Edit Category</button>
</form>
</body>
</html>
