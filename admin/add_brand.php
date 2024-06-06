<?php
global $conn;
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand_name = strtoupper($_POST['b_name']);
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO brands (b_name, email, phone) VALUES ('$brand_name', '$email', '$phone')";

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
    <title>Add New Brand</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Add New Brand</h1>
<form action="" method="post">
    <label for="b_name">Brand Name:</label>
    <input type="text" id="b_name" name="b_name" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br>

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone"><br>

    <button type="submit">Add Brand</button>
</form>
</body>
</html>
