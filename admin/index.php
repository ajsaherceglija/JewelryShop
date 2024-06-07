<?php
session_start();

if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: ../login.php");
    exit();
}
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../includes/db_connection.php';


$sql_products = "SELECT p.pid, p.p_name, p.description, p.regular_price, p.current_price, 
                 p.quantity, p.p_image, c.c_name AS category_name, b.b_name AS brand_name 
                 FROM products p 
                 LEFT JOIN categories c ON p.category = c.CRID 
                 LEFT JOIN brands b ON p.brand = b.BID";
$result_products = $conn->query($sql_products);

$products_columns = ['ID', 'Name', 'Description', 'Regular price', 'Current price', 'Quantity', 'Image', 'Category', 'Brand'];

$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

$categories_columns = [];
if ($result_categories->num_rows > 0) {
    $row = $result_categories->fetch_assoc();
    $categories_columns = array_keys($row);
}
$sql_brands = "SELECT * FROM brands";
$result_brands = $conn->query($sql_brands);

$brands_columns = [];
if ($result_brands->num_rows > 0) {
    $row = $result_brands->fetch_assoc();
    $brands_columns = array_keys($row);
}
$sql_users = "SELECT * FROM people";
$result_users = $conn->query($sql_users);
$users_columns = [];
if ($result_users->num_rows > 0) {
    $row = $result_users->fetch_assoc();
    $users_columns = array_keys($row);
}

$sql_products_under_5 = "SELECT p.PID, p.p_name, p.description, p.regular_price, p.current_price, 
                        p.quantity, p.p_image, c.c_name AS category_name, b.b_name AS brand_name 
                        FROM products p 
                        LEFT JOIN categories c ON p.category = c.CRID 
                        LEFT JOIN brands b ON p.brand = b.BID
                        WHERE p.quantity <= 5";

$result_products_under_5 = $conn->query($sql_products_under_5);

$products_under_5_columns = ['ID', 'Name', 'Description', 'Regular price', 'Current price', 'Quantity', 'Image', 'Category', 'Brand'];

$sql = "SELECT p.p_name, AVG(r.rating) AS avg_rating
        FROM products p
        LEFT JOIN reviews r ON p.PID = r.product
        GROUP BY p.PID
        HAVING avg_rating > 0.0";
$result = $conn->query($sql);

$productNames = [];
$averageRatings = [];

while ($row = $result->fetch_assoc()) {
    $productNames[] = $row['p_name'];
    $averageRatings[] = $row['avg_rating'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Interface</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<header>
    <div class="name">
        <a href="../home_page.php">JEWELRY PORODICA KARALIĆ</a>
    </div>
    <nav class="navbar">
        <a href="?logout" title="Logout"><i class='bx bx-log-out'></i></a>
    </nav>
</header>
<div class="wrapper">
    <h1>Welcome, Admin!</h1>
    <div class="admin-options">
        <a href="javascript:void(0)" onclick="toggleTable('products-table')">Products</a>
        <div id="products-table" class="admin-table" style="display: none;">
            <div class="admin-options">
                <a href="add_product.php">Add Product</a>
            </div>
            <table>
                <tr>
                    <?php foreach ($products_columns as $column): ?>
                        <th><?php echo $column; ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
                <?php
                $result_products->data_seek(0);
                if ($result_products->num_rows > 0) {
                    while ($row = $result_products->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($row as $key => $value) {
                            if ($key === 'p_image') {
                                echo "<td><img src='$value' alt='Product Image' style='max-width: 100px; max-height: 100px;'></td>";
                            }else {
                                echo "<td>" . $value . "</td>";
                            }
                        }
                        echo "<td >
                                <div class='edit'>
                                    <a href='edit_product.php?id=" . $row["pid"] . "'>Edit</a> | 
                                    <a href='' onclick='deleteProduct(" . $row["pid"] . ")'>Delete</a>
                                </div>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . (count($products_columns) + 1) . "'>No products found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <div class="admin-options">
        <a href="javascript:void(0)" onclick="toggleTable('products-under-5-table')">Low Stock Products</a>
        <div id="products-under-5-table" class="admin-table" style="display: none;">
            <table>
                <tr>
                    <?php foreach ($products_under_5_columns as $column): ?>
                        <th><?php echo $column; ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
                <?php
                if ($result_products_under_5->num_rows > 0) {
                    while ($row = $result_products_under_5->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($row as $key => $value) {
                            if ($key === 'image') {
                                echo "<td><img src='$value' alt='Product Image' style='max-width: 100px; max-height: 100px;'></td>";
                            } else {
                                echo "<td>" . $value . "</td>";
                            }
                        }
                        echo "<td >
                                <div class='edit'>
                                    <a href='edit_product.php?id=" . $row["PID"] . "'>Edit</a> | 
                                    <a href='' onclick='deleteProduct(" . $row["PID"] . ")'>Delete</a>
                                </div>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . (count($products_under_5_columns) + 1) . "'>No low stock products</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <div class="admin-options">
        <a href="javascript:void(0)" onclick="toggleTable('categories-table')">Categories</a>
        <div id="categories-table" class="admin-table" style="display: none;">
            <div class="admin-options">
                <a href="add_category.php">Add Category</a>
            </div>
            <table>
                <tr>
                    <?php foreach ($categories_columns as $column): ?>
                        <th><?php echo $column; ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
                <?php
                $result_categories->data_seek(0);
                if ($result_categories->num_rows > 0) {
                    while ($row = $result_categories->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>" . $value . "</td>";
                        }
                        echo "<td >
                                <div class='edit'>
                                    <a href='edit_category.php?id=" . $row["CRID"] . "'>Edit</a> | 
                                    <a href='' onclick='deleteCategory(" . $row["CRID"] . ")'>Delete</a>
                                </div>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . (count($categories_columns) + 1) . "'>No categories found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <div class="admin-options">
        <a href="javascript:void(0)" onclick="toggleTable('brands-table')">Brands</a>
        <div id="brands-table" class="admin-table" style="display: none;">
            <div class="admin-options">
                <a href="add_brand.php">Add Brand</a>
            </div>
            <table>
                <tr>
                    <?php foreach ($brands_columns as $column): ?>
                        <th><?php echo $column; ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
                <?php
                $result_brands->data_seek(0);
                if ($result_brands->num_rows > 0) {
                    while ($row = $result_brands->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>" . $value . "</td>";
                        }
                        echo "<td >
                                <div class='edit'>
                                    <a href='edit_brands.php?id=" . $row["BID"] . "'>Edit</a> | 
                                    <a href='' onclick='deleteBrand(" . $row["BID"] . ")'>Delete</a>
                                </div>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . (count($brands_columns) + 1) . "'>No brands found</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <div class="admin-options">
        <a href="javascript:void(0)" onclick="toggleTable('users-table')">Users</a>
        <div id="users-table" class="admin-table" style="display: none;">
            <div class="admin-options">
                <a href="add_user.php">Add User</a>
            </div>
            <table>
                <tr>
                    <?php foreach ($users_columns as $column): ?>
                        <?php if ($column !== 'p_password'): ?>
                            <th><?php echo $column; ?></th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
                <?php
                $result_users->data_seek(0);
                if ($result_users->num_rows > 0) {
                    while ($row = $result_users->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($row as $key => $value) {
                            if ($key !== 'p_password') {
                                echo "<td>" . $value . "</td>";
                            }
                        }
                        echo "<td>
                <div class='edit'>
                    <a href='edit_user.php?id=" . $row["PID"] . "'>Edit</a>
                </div>
            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . (count($users_columns) + 1) . "'>No users found</td></tr>";
                }
            ?>
            </table>
        </div>
    </div>
    <canvas id="averageRatingChart" width="300" height="150"></canvas>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var productNames = <?= json_encode($productNames); ?>;
        var averageRatings = <?= json_encode($averageRatings); ?>;

        var ctx = document.getElementById('averageRatingChart').getContext('2d');
        var averageRatingChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productNames,
                datasets: [{
                    label: 'Average Rating',
                    data: averageRatings,
                    backgroundColor: 'rgba(255, 193, 7, 0.2)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5
                    }
                }
            }

        });
    </script>
    </div>
<script src="admin.js"></script>
</body>
</html>
