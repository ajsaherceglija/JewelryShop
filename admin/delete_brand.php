<?php
session_start();

require '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['BID'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS count 
                                  FROM products 
                                  WHERE brand = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
    $stmt->close();

    if ($count > 0) {
        echo "Cannot delete brand. There are products associated with this brand.";
    } else {
        $stmt = $conn->prepare("DELETE FROM brands 
                                      WHERE BID = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error deleting brand: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    echo "Invalid request.";
}
$conn->close();
?>