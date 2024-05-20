<?php
require 'includes/db_connection.php';

if (isset($_GET['query'])) {
    $search_query = $conn->real_escape_string($_GET['query']);

    $sql = "SELECT * FROM products WHERE p_name LIKE '%$search_query%' OR description LIKE '%$search_query%'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='results_number' >$result->num_rows " . 'results' . "</div>";

        echo "<div class='product-grid'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<img src='images/{$row["p_image"]}' alt='{$row["p_name"]}' style='width: 100px; height: 100px;'>";
            echo "<div class='product-details'>";
            echo "<div class='product-name'>" . $row["p_name"] . "</div>";
            echo "<div class='product-description'>" . $row["description"] . "</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "No results found";
    }
} else {
    echo "No search query provided";
}

$conn->close();
?>
