<?php require 'includes/header_sessions.php'; ?>
<?php require 'includes/db_connection.php'; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Karalic</title>
    <link rel="stylesheet" href="includes/header.css">
    <link rel="stylesheet" href="home-page.css">
    <link rel="stylesheet" href="category.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />

    <script>
        function addToCart(productId) {
            alert('Product added to cart. Product ID: ' + productId);
        }

        function openProductPage(productId) {
            window.location.href = 'product.php?pid=' + productId;
        }
    </script>
</head>

<body class="home">
<?php require 'includes/header.php'?>

<main>
    <div class="search-box" id="search-box">
        <input id="search-input" type="text" placeholder="Search...">
        <div id="search-results"></div>
    </div>

    <?php
    require 'includes/db_connection.php';

    $products_per_page = 12;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $products_per_page;


    if (isset($_GET['CRID'])) {
        $category_id = $_GET['CRID'];
        $sql_count = "SELECT COUNT(*) as total FROM products WHERE category = $category_id";
        $count_result = $conn->query($sql_count);
        $total_products = $count_result->fetch_assoc()['total'];
        $total_pages = ceil($total_products / $products_per_page);

        $sql = "SELECT * FROM products WHERE category = $category_id LIMIT $offset, $products_per_page";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='results_number'>$total_products results</div>";
            echo "<div class='product-grid'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='sproduct' onclick='openProductPage({$row["pid"]})'>";
                $image = isset($row["image"]) ? $row["image"] : '';
                $description = isset($row["description"]) ? $row["description"] : '';
                echo "<img src='images/{$row["p_image"]}' alt='{$row["p_name"]}'>";
                echo "<div class='product-details'>";
                echo "<div class='product-name'>" . $row["p_name"] . "</div>";
                echo "<div class='product-description'>" . $description . "</div>";
                echo "<div class='product-price'>$" . $row["current_price"] . "</div>";


                echo "<form action='add_to_cart.php' method='post'>";
                echo "<input type='hidden' name='pid' value='{$row['pid']}'>";
                echo "<button type='submit'><i class='bx bx-shopping-bag icon'></i></button>";
                echo "</form>";


                echo "</div>";
                echo "</div>";
            }
            echo "</div>";

            // Pagination
            if ($total_pages > 1) {
                echo "<div class='pagination'>";
                if ($current_page > 1) {
                    echo "<a href='category.php?CRID=$category_id&page=" . ($current_page - 1) . "'>Previous</a>";
                }

                for ($page = 1; $page <= $total_pages; $page++) {
                    if ($page == $current_page) {
                        echo "<span class='current-page'>$page</span>";
                    } else {
                        echo "<a href='category.php?CRID=$category_id&page=$page'>$page</a>";
                    }
                }

                if ($current_page < $total_pages) {
                    echo "<a href='category.php?CRID=$category_id&page=" . ($current_page + 1) . "'>Next</a>";
                }
                echo "</div>";
            }
        } else {
            echo "<div class='results_number'>No results found</div>";
        }
    } else {
        echo "<div class='results_number'>No category specified</div>";
    }

    $conn->close();
    ?>
</main>

<script src="includes/header.js"></script>
</body>
</html>









