<?php
global $conn;
session_start();
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../includes/db_connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["p_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!empty($_FILES["p_image"]["tmp_name"])) {
        $check = getimagesize($_FILES["p_image"]["tmp_name"]);
        if ($check !== false) {
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                if (move_uploaded_file($_FILES["p_image"]["tmp_name"], $target_file)) {
                    $sql = "UPDATE products SET p_name='{$_POST['p_name']}', description='{$_POST['description']}', regular_price='{$_POST['regular_price']}', 
                            current_price='{$_POST['current_price']}', quantity='{$_POST['quantity']}', category='{$_POST['category']}', brand='{$_POST['brand']}', p_image='$target_file' 
                            WHERE pid='{$_POST['pid']}'";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        $sql = "UPDATE products 
                SET p_name='{$_POST['p_name']}', description='{$_POST['description']}', regular_price='{$_POST['regular_price']}', 
                    current_price='{$_POST['current_price']}', quantity='{$_POST['quantity']}', category='{$_POST['category']}', brand='{$_POST['brand']}' 
                WHERE pid='{$_POST['pid']}'";
    }
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating product: " . $conn->error;
    }
}
$product = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE pid='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
    }
}
$sql_categories = "SELECT CRID, c_name FROM categories";
$result_categories = $conn->query($sql_categories);
$categories = [];
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
    }
}

$sql_brands = "SELECT BID, b_name FROM brands";
$result_brands = $conn->query($sql_brands);
$brands = [];
if ($result_brands->num_rows > 0) {
    while ($row = $result_brands->fetch_assoc()) {
        $brands[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Edit Product</h1>
<form action="" method="post">
    <label for="pid">Product ID:</label>
    <input type="text" id="pid" name="pid" value="<?php echo $product['pid'] ?>" readonly><br>

    <label for="p_name">New Name:</label>
    <input type="text" id="p_name" name="p_name" value="<?php echo $product['p_name'] ?>"><br>

    <label for="description">New Description:</label>
    <textarea id="description" name="description" rows="4" cols="50"><?php echo $product['description'] ?></textarea><br>

    <label for="regular_price">New Regular Price:</label>
    <input type="number" id="regular_price" name="regular_price" step="0.01" value="<?php echo $product['regular_price'] ?>"><br>

    <label for="current_price">New Current Price:</label>
    <input type="number" id="current_price" name="current_price" step="0.01" value="<?php echo $product['current_price'] ?>"><br>

    <label for="quantity">New Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="<?php echo $product['quantity'] ?>"><br>

    <label for="category">New Category:</label>
    <select id="category" name="category" required>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['CRID']; ?>"><?php echo htmlspecialchars($category['c_name']); ?></option>
        <?php endforeach; ?>
    </select><br>

    <label for="brand">New Brand:</label>
    <select id="brand" name="brand" required>
        <?php foreach ($brands as $brand): ?>
            <option value="<?php echo $brand['BID']; ?>"><?php echo htmlspecialchars($brand['b_name']); ?></option>
        <?php endforeach; ?>
    </select><br>

    <label for="p_image">New Image:</label>
    <input type="file" id="p_image" name="p_image" accept="image/*"><br>

    <button type="submit">Edit Product</button>
</form>
</body>
</html>
