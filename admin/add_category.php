<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = strtoupper($_POST['c_name']);

    $sql = "INSERT INTO categories (c_name) VALUES ('$category_name')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Add New Category</h1>
<form action="" method="post">
    <label for="c_name">Category Name:</label>
    <input type="text" id="c_name" name="c_name" required><br>

    <button type="submit">Add Category</button>
</form>
</body>
</html>
