<?php
session_start();

require '../includes/db_connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pid = $_POST['pid'];

    $stmt = $conn->prepare("DELETE FROM products WHERE pid = ?");
    $stmt->bind_param("i", $pid);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
    $stmt->close();
}else {
    echo "Invalid request.";
}

$conn->close();
?>