<?php require 'includes/header_sessions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=4.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Karalic</title>
    <link rel="stylesheet" href="includes/header.css">
    <link rel="stylesheet" href="home-page.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,0,0" />

    <style>
        .results_number {
            margin: 20px auto;
            font-size: 16px;
            color: #b99976;
            text-align: center;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            justify-items: center;
        }

        .sproduct {
            text-decoration: none;
            color: inherit;
            border: none;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            width: 100%;
            box-sizing: border-box;
            position: relative;
            cursor: pointer;
        }

        .sproduct:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .sproduct img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .product-details {
            text-align: center;
            margin-bottom: 20px;
        }

        .product-name {
            font-weight: bold;
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-description {
            color: #777;
            margin-bottom: 20px;
        }

        .product-price {
            font-weight: bold;
            font-size: 20px;
            color: black;
            margin-bottom: 10px;
        }

        .cart-icon {
            font-size: 30px;
            color: #333;
            display: none;
            cursor: pointer;
            transition: all 0.3s ease;
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .sproduct:hover .cart-icon {
            display: block;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
            padding-bottom: 20px;
        }

        .pagination a, .pagination span {
            display: inline-block;
            margin: 0 5px;
            padding: 10px 20px;
            text-decoration: none;
            color: #333;
            background-color: #f2f2f2;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .pagination .current-page {
            font-weight: bold;
            background-color: #333;
            color: white;
        }
    </style>

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

    $products_per_page = 12; // Promijenjeno na 12 proizvoda po stranici
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
                echo "<div class='sproduct' onclick='openProductPage({$row["PID"]})'>";
                $image = isset($row["image"]) ? $row["image"] : '';
                $description = isset($row["p_description"]) ? $row["p_description"] : '';
                echo "<img src='images/{$row["image"]}' alt='{$row["p_name"]}'>";
                echo "<div class='product-details'>";
                echo "<div class='product-name'>" . $row["p_name"] . "</div>";
                echo "<div class='product-description'>" . $description . "</div>";
                echo "<div class='product-price'>$" . $row["current_price"] . "</div>";
                echo "<i class='bx bxs-cart-add cart-icon' onclick=\"addToCart(" . $row['PID'] . ")\"></i>";
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



