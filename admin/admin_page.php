<?php
global $conn;
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

$sql_products_under_5 = "SELECT p.pid, p.p_name, p.description, p.regular_price, p.current_price, 
                         p.quantity, p.p_image, c.c_name AS category_name, b.b_name AS brand_name 
                         FROM low_stock_products p 
                         LEFT JOIN categories c ON p.category_name = c.c_name 
                         LEFT JOIN brands b ON p.brand_name = b.b_name";

$result_products_under_5 = $conn->query($sql_products_under_5);

$sql = "SELECT p.pid, p.p_name, p.avg_rating
        FROM products p
        WHERE avg_rating > 0.0";
$result = $conn->query($sql);

$productNames = [];
$averageRatings = [];

while ($row = $result->fetch_assoc()) {
    $productNames[] = $row['p_name'];
    $averageRatings[] = $row['avg_rating'];
}

$sql_orders = "SELECT * FROM orders WHERE status = 'finished'";
$result_orders = $conn->query($sql_orders);
$orders_columns = ['ID', 'Address', 'Status', 'Order Date', 'Total Price'];

if (isset($_GET['action']) && isset($_GET['OID'])) {
    $action = $_GET['action'];
    $order_id = $_GET['OID'];

    if ($action === 'dispatch') {
        $sql_update_order = "UPDATE orders SET status = 'shipped' WHERE OID = $order_id";
    } elseif ($action === 'cancel') {
        $sql_update_order = "UPDATE orders SET status = 'canceled' WHERE OID = $order_id";
    }

    if (isset($sql_update_order)) {
        if ($conn->query($sql_update_order) === TRUE) {
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Error updating order status: " . $conn->error;
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql_s_orders = "SELECT * FROM orders WHERE (status = 'shipped' OR status = 'finished')";

    if (!empty($_GET['date'])) {
        $date = $_GET['date'];
        $sql_s_orders .= " AND DATE(o_date) = '$date'";
    }

    if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
        $from_date = $_GET['from_date'];
        $to_date = $_GET['to_date'];
        $sql_s_orders .= " AND o_date BETWEEN '$from_date' AND '$to_date'";
    }

    $result_s_orders = $conn->query($sql_s_orders);
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
            <div class="table-wrapper">
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
    </div>
    <div class="admin-options">
        <a href="javascript:void(0)" onclick="toggleTable('products-under-5-table')">Low Stock Products</a>
        <div id="products-under-5-table" class="table-wrapper" style="display: none;">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Regular Price</th>
                    <th>Current Price</th>
                    <th>Quantity</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Actions</th>
                </tr>
                <?php
                if ($result_products_under_5->num_rows > 0) {
                    while ($row = $result_products_under_5->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= $row["pid"] ?></td>
                            <td><?= $row["p_name"] ?></td>
                            <td><?= $row["description"] ?></td>
                            <td><?= $row["regular_price"] ?></td>
                            <td><?= $row["current_price"] ?></td>
                            <td><?= $row["quantity"] ?></td>
                            <td><img src="<?= $row["p_image"] ?>" alt="Product Image" style="max-width: 100px; max-height: 100px;"></td>
                            <td><?= $row["category_name"] ?></td>
                            <td><?= $row["brand_name"] ?></td>
                            <td>
                                <div class='edit'>
                                    <a href='edit_product.php?id=<?= $row["pid"] ?>'>Edit</a> |
                                    <a href='' onclick='deleteProduct(<?= $row["pid"] ?>)'>Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="10">No low stock products</td>
                    </tr>
                    <?php
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
            <div class="table-wrapper">
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
    </div>
    <div class="admin-options">
        <a href="javascript:void(0)" onclick="toggleTable('brands-table')">Brands</a>
        <div id="brands-table" class="admin-table" style="display: none;">
            <div class="admin-options">
                <a href="add_brand.php">Add Brand</a>
            </div>
            <div class="table-wrapper">
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
    </div>
    <div class="admin-options">
        <a href="javascript:void(0)" onclick="toggleTable('users-table')">Users</a>
        <div id="users-table" class="admin-table" style="display: none;">
            <div class="admin-options">
                <a href="add_user.php">Add User</a>
            </div>
            <div class="table-wrapper">
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
    </div>

    <div class="admin-options">
        <a href="javascript:void(0)" onclick="toggleTable('orders-table')">Manage Orders</a>
        <div id="orders-table" class="table-wrapper" style="display: none;">
            <table>
                <tr>
                    <?php foreach ($orders_columns as $column): ?>
                        <th><?= $column ?></th>
                    <?php endforeach; ?>
                    <th>Actions</th>
                </tr>
                <?php
                $result_orders->data_seek(0);
                if ($result_orders->num_rows > 0) {
                    while ($row = $result_orders->fetch_assoc()) {
                        ?>
                        <tr>
                            <?php foreach ($row as $key => $value): ?>
                                <td><?= $value ?></td>
                            <?php endforeach; ?>
                            <td>
                                <div class='edit'>
                                    <a href='view_order.php?id=<?= $row["OID"] ?>'>View</a> |
                                    <a href='?action=dispatch&OID=<?= $row["OID"] ?>'>Dispatch</a> |
                                    <a href='?action=cancel&OID=<?= $row["OID"] ?>'>Cancel</a>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="<?= count($orders_columns) + 1 ?>">No orders found</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <div class="admin-options">
        <a href="javascript:void(0)" onclick="toggleTable('daily-table')">Daily Orders</a>
        <div id="daily-table" class="admin-table" style="display: none;">
            <form method="get" action="">
                <label for="date">Enter Date:</label>
                <input type="date" id="date" name="date">
                <label for="from_date">From:</label>
                <input type="date" id="from_date" name="from_date">
                <label for="to_date">To:</label>
                <input type="date" id="to_date" name="to_date">
                <button type="submit">Filter</button>
            </form>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Total Price</th>
                </tr>
                <?php
                $result_s_orders->data_seek(0);
                if ($result_s_orders->num_rows > 0) {
                    while ($row = $result_s_orders->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= $row["OID"] ?></td>
                            <td><?= $row["o_address"] ?></td>
                            <td><?= $row["status"] ?></td>
                            <td><?= $row["o_date"] ?></td>
                            <td><?= $row["total_price"] ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6">No orders found</td>
                    </tr>
                    <?php
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
