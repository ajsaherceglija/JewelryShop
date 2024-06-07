<?php
global $conn;
session_start();

require '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['CRID'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS count 
                                  FROM products 
                                  WHERE category = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    $stmt->close();

    if ($count > 0) {
        echo "Cannot delete category. There are products associated with this category.";
    } else {
        $stmt = $conn->prepare("DELETE FROM categories 
                                      WHERE CRID = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error deleting category: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    echo "Invalid request.";
}
$conn->close();
?>